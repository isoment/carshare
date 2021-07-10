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

        return $vehicle->isAvailable($request['from'], $request['to'])
            ? response()->json([])
            : response()->json([], 404);
    }
}
