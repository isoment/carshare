<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AvailabilityCheckRequest;
use App\Models\Vehicle;
use App\Services\AvailabilityService;
use Illuminate\Http\JsonResponse;

class AvailabilityController extends Controller
{
    protected AvailabilityService $availabilityService;

    public function __construct(AvailabilityService $availabilityService)
    {
        $this->availabilityService = $availabilityService;
    }

    /**
     *  Check if a vehicle is available
     * 
     *  @param int $vehicleId
     *  @param Illuminate\Http\AvailabilityCheckRequest $request
     * 
     *  @return Illuminate\Http\JsonResponse
     */
    public function guestCheck(int $vehicleId, AvailabilityCheckRequest $checkRequest) : JsonResponse
    {
        return $this->availabilityService->guestAvailCheck($vehicleId, $checkRequest);
    }

    /**
     *  Check if a vehicle is available
     * 
     *  @param int $vehicleId
     *  @param Illuminate\Http\Request $request
     * 
     *  @return Illuminate\Http\JsonResponse
     */
    public function authCheck(int $vehicleId, AvailabilityCheckRequest $checkRequest) : JsonResponse
    {
        return $this->availabilityService->authAvailCheck($vehicleId, $checkRequest);
    }

    /**
     *  User bookings dates
     * 
     *  @return Illuminate\Http\JsonResponse
     */
    public function userBookedDates()
    {
        return response()->json([
            'unavailableDates' => current_user()->individualBookingDates()
        ]);
    }

    /**
     *  Calculate price of the rental
     * 
     *  @param int $vehicleId the vehicle id
     *  @param Illuminate\Http\Request $request
     * 
     *  @return Illuminate\Http\JsonResponse
     */
    public function price(int $vehicleId, AvailabilityCheckRequest $request) : JsonResponse
    {
        $vehicle = Vehicle::findOrFail($vehicleId);

        return response()->json([
            'data' => $vehicle->calculatePrice($request['from'], $request['to'])
        ]);
    }
}
