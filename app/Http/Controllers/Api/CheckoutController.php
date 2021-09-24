<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckoutStoreRequest;
use App\Models\Booking;
use App\Models\Order;
use App\Models\Vehicle;
use App\Notifications\OrderConfirmation;
use App\Rules\BookingDatesAvailable;
use App\Rules\VehicleAvailabe;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    public function store(CheckoutStoreRequest $request) 
    {
        $user = auth()->user();

        // Make sure the user has an ID on file
        if (!$user->driversLicense) {
            return response()->json('You must verify ID prior to booking', 403);
        }

        try {
            // Create the order
            $order = Order::create([
                'user_id' => $user->id,
                'transaction_id' => $request['payment_method_id'],
                'total' => 0,
            ]);

            // Total price of the order
            $totalPrice = 0;

            // Calculate the pricing based on the desired booking dates for a vehicle
            // and then create a booking.
            foreach ($request['cart'] as $item) {
                $vehicle = Vehicle::findOrFail($item['vehicle_id']);

                $vehicleTotal = $vehicle->calculatePrice($item['dates']['start'], $item['dates']['end'])['total'];

                $totalPrice += $vehicleTotal;

                Booking::create([
                    'vehicle_id' => $vehicle->id,
                    'order_id' => $order->id,
                    'from' => Carbon::parse($item['dates']['start']),
                    'to' => Carbon::parse($item['dates']['end']),
                    'price_total' => $vehicleTotal,
                    'price_day' => $vehicle->price_day
                ]);
            }
            
            // Update the order total
            $order->update([
                'total' => $totalPrice
            ]);

            // Charge the user
            $user->charge(
                $totalPrice * 100,
                $request['payment_method_id'],
            );

            // Email confirmation
            $user->notify(new OrderConfirmation());

            return response()->json('Success', 201);
        } catch(Exception $e) {
            // If there is an error we want to delete bookings...
            Booking::where('order_id', $order->id)->delete();

            // And also the order...
            $order->delete();

            return response()->json('Error processing payment', 500);
        }
    }
}
