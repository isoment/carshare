<?php

namespace Tests\Trait;

use App\Models\User;
use Carbon\Carbon;

trait UserReviewTrait 
{
    /**
     *  Return an array of the review index json structure
     *  
     *  @param bool $type
     *  @return array
     */
    private function reviewIndexStructure(bool $ofHost = true) : array
    {
        $userType = 'host';
        $reviewType = 'hostReview';

        if ($ofHost !== true) {
            $userType = 'renter';
            $reviewType = 'renterReview';
        }

        return [
            'data' => [
                '*' => [
                    'booking' => [
                        'id', 'from', 'to', 'order_id', 'vehicle_id'
                    ],
                    $userType => [
                        'id', 'image', 'name'
                    ],
                    $reviewType => ['id', 'content', 'rating'],
                    'vehicle' => [
                        'featured_image', 'make', 'model', 'year'
                    ]
                ]
            ],
            'links' => [
                'first', 'last', 'prev', 'next'
            ],
            'meta' => [
                'current_page',
                'from',
                'last_page',
                'links' => [
                    '*' => [
                        'url', 'label', 'active'
                    ]
                ],
                'path',
                'per_page',
                'to',
                'total'
            ]
        ];
    }

    /**
     *  Setup a finished booking and uncompleted review for the user to
     *  leave of a host.
     * 
     *  @param User $user
     *  @return void
     */
    private function setUnreviewedCompletedBooking(User $user) : void
    {
        $order = $user->orders->first();

        $booking = $order->bookings->first();

        $hostReview = $booking->hostReview;

        $booking->update([
            'from' => Carbon::now()->subYears(10),
            'to' => Carbon::now()->subYears(10)
        ]);

        $hostReview->update([
            'rating' => NULL,
            'content' => NULL
        ]);
    }
}