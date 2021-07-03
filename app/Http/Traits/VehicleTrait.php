<?php

namespace App\Http\Traits;

use App\Models\HostReview;
use App\Models\RenterReview;
use App\Models\User;
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
        return User::find($userId);
    }

    /**
     *  Count the total host trips, we use HostReview since
     *  as soon as a booking is created a review entry is created.
     *  
     *  @param int $userId
     */
    public function totalTrips(int $userId)
    {
        return HostReview::where('user_id', $userId)->count();
    }

    /**
     *  Calculate a users rating
     * 
     *  @param int $userId
     */
    public function calculateUserRating(int $userId)
    {
        $hostRatings = HostReview::where('user_id', $userId)->get()->pluck('rating');

        $ratings = $hostRatings->merge(
            RenterReview::where('user_id', $userId)->get()->pluck('rating')
        );

        return round($ratings->sum() / $ratings->count(), 1);
    }
}