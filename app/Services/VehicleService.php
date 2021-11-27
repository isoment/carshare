<?php

namespace App\Services;

use App\Http\Traits\ReviewTrait;
use App\Http\Traits\VehicleTrait;
use App\Models\Booking;
use App\Models\Vehicle;
use Carbon\Carbon;

class VehicleService
{
    use VehicleTrait, ReviewTrait;

    /**
     *  Return an index of vehicles and filter by parameters
     *  in the request.
     */
    public function index($request)
    {
        $from = Carbon::parse($request['from'])->toDateString();
        $to = Carbon::parse($request['to'])->toDateString();

        // Only want to search by price if its selected
        $hasMinMax = isset($request['min']) && isset($request['max']);

        return Vehicle::where('active', 1)->whereDoesntHave('bookings', function($query) use ($from, $to) {

            $query->betweenDates($from, $to);

        })->when($hasMinMax, function($query) use ($request) {

            $query->whereBetween('price_day', [$request['min'], $request['max']]);

        })->with('vehicleModel.vehicleMake')
            ->with('vehicleImages')
            ->withCount('bookings')
            ->paginate(12);
    }

    /**
     *  Get information for an individual vehicle
     */
    public function show(Vehicle $vehicle)
    {
        return collect($vehicle)->merge([
            'vehicle_images' => $this->vehicleImages($vehicle->id),
            'host' => $this->hostInfo($vehicle->user_id),
            'host_total_trips' => $this->totalTrips($vehicle->user_id),
            'host_rating' => $this->calculateUserTotalRating($vehicle->user_id),
            'vehicle_rating' => Booking::calculateVehicleRating($vehicle->id),
            'vehicle_review_count' => $this->vehiclesReviewCount($vehicle->id),
            'vehicle_trip_count' => $this->vehicleTripCount($vehicle->id),
        ]);
    }
}