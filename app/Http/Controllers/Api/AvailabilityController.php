<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AvailabilityCheckRequest;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class AvailabilityController extends Controller
{
    /**
     *  Check if a vehicle is available
     * 
     *  @param int $vehicleId the vehicle id
     *  @param Illuminate\Http\Request $request
     */
    public function check($vehicleId, AvailabilityCheckRequest $request)
    {
        $vehicle = Vehicle::findOrFail($vehicleId);

        if (!$vehicle->isAvailable($request['from'], $request['to'])) {
            return response()->json('Vehicle unavailable on these dates.', 404);
        }

        if (!auth()->user()->hasNoBooking($request['from'], $request['to'])) {
            return response()->json('You already have a booking on these dates.', 404);
        }

        return response()->json();
    }

    /**
     *  Calculate price of the rental
     * 
     *  @param int $vehicleId the vehicle id
     *  @param Illuminate\Http\Request $request
     */
    public function price($vehicleId, AvailabilityCheckRequest $request)
    {
        $vehicle = Vehicle::findOrFail($vehicleId);

        return response()->json([
            'data' => $vehicle->calculatePrice($request['from'], $request['to'])
        ]);
    }
}
