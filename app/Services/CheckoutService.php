<?php
// declare(strict_types=1);

namespace App\Services;

use App\Http\Requests\CheckoutStoreRequest;
use App\Models\Booking;
use App\Models\Order;
use App\Models\Vehicle;
use App\Notifications\OrderConfirmation;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CheckoutService
{
    /**
     *  Charge the customer and store order information in Model
     * 
     *  @param CheckoutStoreRequest $request
     * 
     *  @return Illuminate\Http\JsonResponse
     */
    public function store(CheckoutStoreRequest $request) : JsonResponse
    {
        // Make sure the user has an ID on file
        if (!current_user()->driversLicense) {
            return response()->json('You must verify ID prior to booking', 403);
        }

        // Make sure the user doesn't own a vehicle they are trying to book
        if ($this->userOwnsBookingVehicle($request)) {
            return response()->json('You cannot book your own vehicle', 403);
        }

        try {
            $order = $this->createOrder($request);

            // Total price of the order
            $totalPrice = $this->totalPrice($request, $order);

            // Charge the user
            $paymentIntent = $this->oneTimeCharge($request, $totalPrice);

            // Update the order total and payment intent
            $order->update([
                'total' => $totalPrice,
                'payment_intent' => $paymentIntent
            ]);

            // Email confirmation
            current_user()->notify(new OrderConfirmation());

            // Clear the stats cache for the renter
            Cache::forget('renter-stats-user:' . current_user()->id);

            return response()->json('Success', 201);
        } catch(Exception $e) {
            $this->revertModels($order);

            return response()->json('Error processing payment', 500);
        }
    }

    /**
     *  Check if any of the vehicles are owned by the user
     * 
     *  @param App\Http\Requests\CheckoutStoreRequest $request
     * 
     *  @return bool
     */
    private function userOwnsBookingVehicle(CheckoutStoreRequest $request) : bool
    {
        $hostIds = [];

        foreach ($request['cart'] as $item) {
            array_push($hostIds, $item['host_id']);
        }

        return collect($hostIds)->contains(current_user()->id) ? true : false;
    }

    /**
     *  Create an order model
     * 
     *  @param App\Http\Requests\CheckoutStoreRequest $request
     * 
     *  @return App\Models\Order
     */
    private function createOrder(CheckoutStoreRequest $request) : Order
    {
        return Order::create([
            'user_id' => current_user()->id,
            'payment_method' => $request['payment_method_id'],
            'payment_intent' => 'N/A',
            'total' => 0,
        ]);
    }

    /**
     *  Calculate price returning the total price
     * 
     *  @param App\Http\Requests\CheckoutStoreRequest $request
     *  @param App\Models\Order $order
     * 
     *  @return int
     */
    private function totalPrice(CheckoutStoreRequest $request, Order $order) : int
    {
        $totalPrice = 0;

        foreach ($request['cart'] as $item) {
            $vehicle = Vehicle::findOrFail($item['vehicle_id']);

            $vehicleTotal = $vehicle->calculatePrice($item['dates']['start'], $item['dates']['end'])['total'];

            $totalPrice += $vehicleTotal;

            $this->createBooking($vehicle, $order, $item, $vehicleTotal);
        }

        return $totalPrice;
    }

    /**
     *  Create a booking
     * 
     *  @param App\Models\Vehicle $vehicle
     *  @param App\Models\Order $order
     *  @param array $item
     *  @param int $vehicleTotal
     * 
     *  @return void
     */
    private function createBooking(Vehicle $vehicle, Order $order, array $item, int $vehicleTotal) : void
    {
        $booking = Booking::create([
            'vehicle_id' => $vehicle->id,
            'order_id' => $order->id,
            'from' => Carbon::parse($item['dates']['start']),
            'to' => Carbon::parse($item['dates']['end']),
            'price_total' => $vehicleTotal,
            'price_day' => $vehicle->price_day
        ]);

        $booking->createRenterReview();
        $booking->createHostReview();
    }

    /**
     *  Charge a user one time
     * 
     *  @param App\Http\Requests\CheckoutStoreRequest $request
     *  @param int $totalPrice
     * 
     *  @return string the payment intent id
     */
    private function oneTimeCharge(CheckoutStoreRequest $request, int $totalPrice) : string
    {
        $payment = current_user()->charge(
            $totalPrice * 100,
            $request['payment_method_id'],
        );

        return $payment->id;
    }

    /**
     *  Revert models if there is a payment error.
     * 
     *  @param App\Models\Order $order
     * 
     *  @return void
     */
    private function revertModels(Order $order) : void
    {
        // If there is an error we want to delete bookings...
        Booking::where('order_id', $order->id)->delete();

        // And also the order...
        $order->delete();
    }
}