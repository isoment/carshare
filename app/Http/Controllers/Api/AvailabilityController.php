<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
    public function check($vehicleId, Request $request)
    {
        $data = $request->validate([
            'from' => ['required', 'date', 'after_or_equal:yesterday'],
            'to' => ['required', 'date', 'after_or_equal:from'],
        ]);

        $vehicle = Vehicle::findOrFail($vehicleId);

        return $vehicle->isAvailable($data['from'], $data['to'])
            ? response()->json([])
            : response()->json([], 404);
    }
}
