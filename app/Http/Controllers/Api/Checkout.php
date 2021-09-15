<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
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

        // Calculate the pricing

        return $request->toArray();
    }
}
