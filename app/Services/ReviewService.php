<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\HostReview;
use App\Models\RenterReview;
use App\Models\User;

class ReviewService
{
    /**
     *  Get the reviews for a vehicle
     * 
     *  @param int $vehicleId
     */
    public function vehicleReviews(int $vehicleId)
    {
        $bookings = Booking::where('vehicle_id', $vehicleId)
            ->get()
            ->pluck('host_review_key');

        return HostReview::with('booking.user.profile')
            ->whereIn('id', $bookings)
            ->whereNotNull('rating')
            ->paginate(4);
    }

    /**
     *  Get the reviews of a user from hosts
     * 
     *  @param int $userId
     */
    public function reviewsFromHosts(int $userId) 
    {
        $id = User::findOrFail($userId)->id;

        return RenterReview::with('booking.vehicle.user.profile')
            ->where('user_id', $id)
            ->whereNotNull('rating')
            ->paginate(4);
    }

}