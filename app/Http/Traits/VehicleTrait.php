<?php

namespace App\Http\Traits;

use App\Models\Booking;
use App\Models\HostReview;
use App\Models\RenterReview;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleImages;

trait VehicleTrait
{
    /**
     *  Get the vehicle images for associated vehicle
     * 
     *  @param int $vehicleId
     */
    public function vehicleImages(int $vehicleId)
    {
        return VehicleImages::where('vehicle_id', $vehicleId)->get()->pluck('image');
    }

    /**
     *  Get the host info of a vehicle
     * 
     *  @param int $userId
     */
    public function hostInfo(int $userId)
    {
        return User::with('profile')->find($userId);
    }

    /**
     *  Count the hosts total trips using the bookings.
     *  
     *  @param int $userId
     */
    public function totalTrips(int $userId)
    {
        $vehicles = Vehicle::where('user_id', $userId)->get()->pluck('id');

        return Booking::whereIn('vehicle_id', $vehicles)->count();
    }

    /**
     *  Calculate a users total rating, ie both as host and renter
     * 
     *  @param int $userId
     */
    public function calculateUserTotalRating(int $userId)
    {
        $hostRatings = HostReview::where('user_id', $userId)
            ->get()
            ->pluck('rating');

        $ratings = $hostRatings->merge(
            RenterReview::where('user_id', $userId)
                ->get()
                ->pluck('rating')
        );

        return round($ratings->sum() / $ratings->count(), 1);
    }

    /**
     *  Count the total ratings for a vehicle
     * 
     *  @param int $vehicleId
     */
    public function vehiclesReviewCount(int $vehicleId)
    {
        $bookings = Booking::where('vehicle_id', $vehicleId)
            ->get()
            ->pluck('host_review_key');

        return HostReview::whereIn('id', $bookings)->whereNotNull('rating')->count();
    }

    /**
     *  Count a vehicles total trips
     * 
     *  @param int $vehicleId
     */
    public function vehicleTripCount(int $vehicleId)
    {
        return Booking::where('vehicle_id', $vehicleId)->count();
    }
}