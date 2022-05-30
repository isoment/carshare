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

        $vehicleBookings = $vehicle->individualBookedDates();

        if (!$vehicle->isAvailable($request['from'], $request['to'])) {
            return response()->json([
                'message' => 'Vehicle unavailable on these dates.',
                'unavailableDates' => $vehicleBookings
            ], 404);
        }

        return response()->json([
            'message' => 'Vehicle available on these dates',
            'unavailableDates' => $vehicleBookings
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

        $bookingDates = $this->bookingsMerge($vehicle);

        if (!$vehicle->isAvailable($request['from'], $request['to'])) {
            return response()->json([
                'message' => 'Vehicle unavailable on these dates',
                'unavailableDates' => $bookingDates
            ], 404);
        }

        // Check if the user is free to book on the given dates.
        if (!auth()->user()->hasNoBooking($request['from'], $request['to'])) {
            return response()->json([
                'message' => 'You already have a booking on these dates',
                'unavailableDates' => $bookingDates
            ], 404);
        }

        // Check if the vehicle is owned by the user. Typecast $vehicle->user_id or associated
        // test will fail. Feature works without this though.
        if ((int) $vehicle->user_id === current_user()->id) {
            return response()->json([
                'message' => 'You cannot book your own vehicle',
                'unavailableDates' => $bookingDates
            ], 404);
        }

        return response()->json([
            'message' => 'Vehicle available',
            'unavailableDates' => $bookingDates
        ]);
    }

    /**
     *  Get the vehicle books and users bookings and merge them together
     * 
     *  @param Vehicle $vehicle
     *  @return array
     */
    private function bookingsMerge(Vehicle $vehicle) : array
    {
        $vehicleBookings = $vehicle->individualBookedDates();

        $userBookings = current_user()->individualBookingDates();

        return array_merge($vehicleBookings, $userBookings);
    }
}