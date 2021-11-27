<?php

namespace App\Http\Traits;

use App\Models\HostReview;
use App\Models\RenterReview;

trait ReviewTrait
{
    /**
     *  Calculate a users total rating, ie both as host and renter
     * 
     *  @param int $userId
     *  @return float
     */
    public function calculateUserTotalRating(int $userId) : float
    {
        $hostRatings = HostReview::where('user_id', $userId)
            ->whereNotNull('rating')
            ->get()
            ->pluck('rating');

        $ratings = $hostRatings->merge(
            RenterReview::where('user_id', $userId)
                ->whereNotNull('rating')
                ->get()
                ->pluck('rating')
        );

        if ($ratings->count() === 0) {
            return 0;
        }

        return round($ratings->sum() / $ratings->count(), 1);
    }

    /**
     *  Calculate a users rating as host a host
     * 
     *  @param int $userId
     *  @return float
     */
    public function calculateUserRatingAsHost(int $userId) : float
    {
        $ratings = HostReview::where('user_id', $userId)
            ->whereNotNull('rating')
            ->get()
            ->pluck('rating');

        if ($ratings->count() === 0) {
            return 0;
        }

        return round($ratings->sum() / $ratings->count(), 1);
    }

    /**
     *  Calculate a users rating as host a renter
     * 
     *  @param int $userId
     *  @return float
     */
    public function calculateUserRatingAsRenter(int $userId) : float
    {
        $ratings = RenterReview::where('user_id', $userId)
            ->whereNotNull('rating')
            ->get()
            ->pluck('rating');

        if ($ratings->count() === 0) {
            return 0;
        }

        return round($ratings->sum() / $ratings->count(), 1);
    }
}