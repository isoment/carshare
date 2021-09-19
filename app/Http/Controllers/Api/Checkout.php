<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Order;
use App\Models\Vehicle;
use App\Notifications\OrderConfirmation;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class Checkout extends Controller
{
    public function store(Request $request) 
    {
        $user = auth()->user();

        // Make sure the user has an ID on file
        if (!$user->driversLicense) {
            return response()->json('You must verify ID prior to booking', 403);
        }

        // Need to check that there are items in the cart and then that 
        // the dates given are valid.
        $data = $request->validate([
            'payment_method_id' => 'required',
            'cart' => 'required|array|min:1',
            'cart.*.vehicle_id' => 'required|exists:vehicles,id',
            'cart.*.dates.start' => 'required|date|after_or_equal:today',
            'cart.*.dates.end' => 'required|date|after_or_equal:cart.*.dates.start'
        ]);

        // Check if available
        $data = array_merge($data, $request->validate([
            'cart.*' => [
                'required',
                function($attribute, $value, $fail) {
                    $vehicle = Vehicle::findOrFail($value['vehicle_id']);

                    if (!$vehicle->isAvailable($value['dates']['start'], $value['dates']['end'])) {
                        $fail($vehicle->year . ' ' . $vehicle->vehicleModel->model . " is not available on these dates");
                    }
                }
            ]
        ]));

        try {
            // Create the order
            $order = Order::create([
                'user_id' => $user->id,
                'transaction_id' => $data['payment_method_id'],
                'total' => 0,
            ]);

            // Total price of the order
            $totalPrice = 0;

            // Calculate the pricing based on the desired booking dates for a vehicle
            // and then create a booking.
            foreach ($data['cart'] as $item) {
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
            $payment = $user->charge(
                $totalPrice * 100,
                $data['payment_method_id'],
            );

            // Email confirmation
            $user->notify(new OrderConfirmation());

            return response()->json($data, 201);
        } catch(Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
