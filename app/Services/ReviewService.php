<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\HostReview;

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

        return HostReview::with('booking.user:id,name')
            ->whereIn('id', $bookings)->whereNotNull('rating')->paginate(5);
    }
}