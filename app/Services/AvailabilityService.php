<?php

namespace App\Services;

use App\Models\Vehicle;
use App\Http\Requests\AvailabilityCheckRequest;
use Illuminate\Http\JsonResponse;

class AvailabilityService
{
    /**
     *  Check if a vehicle is available for a guest
     * 
     *  @param int $vehicleId
     *  @param Illuminate\Http\AvailabilityCheckRequest $request
     *  @return Illuminate\Http\JsonResponse
     */
    public function guestAvailCheck(int $vehicleId, AvailabilityCheckRequest $request) : JsonResponse
    {
        $vehicle = Vehicle::findOrFail($vehicleId);

        $vehicleBookings = $vehicle->bookedDates();

        if (!$vehicle->isAvailable($request['from'], $request['to'])) {
            return response()->json([
                'message' => 'Vehicle unavailable on these dates.',
                'bookedDates' => $vehicleBookings
            ], 404);
        }

        return response()->json([
            'message' => 'Vehicle available',
            'bookedDates' => $vehicleBookings
        ]);
    }

    /**
     *  Check if a vehicle is available for an authenticated user
     * 
     *  @param int $vehicleId
     *  @param Illuminate\Http\AvailabilityCheckRequest $request
     *  @return Illuminate\Http\JsonResponse
     */
    public function authAvailCheck(int $vehicleId, AvailabilityCheckRequest $request) : JsonResponse
    {
        $vehicle = Vehicle::findOrFail($vehicleId);

        if (!$vehicle->isAvailable($request['from'], $request['to'])) {
            return response()->json('Vehicle unavailable on these dates', 404);
        }

        // Check if the user is free to book on the given dates.
        if (!auth()->user()->hasNoBooking($request['from'], $request['to'])) {
            return response()->json('You already have a booking on these dates', 404);
        }

        // Check if the vehicle is owned by the user. Typecast $vehicle->user_id or associated
        // test will fail. Feature works without this though.
        if ((int) $vehicle->user_id === current_user()->id) {
            return response()->json('You cannot book your own vehicle', 404);
        }

        return response()->json('Vehicle available');
    }
}