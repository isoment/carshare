<?php

namespace Tests\Trait;

use App\Models\Booking;
use App\Models\RenterReview;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;

trait UserReviewTrait 
{
    /**
     *  Return an array of the review index json structure
     *  
     *  @param bool $ofHost
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
     *  @return string
     */
    private function setUnreviewedCompletedBookingOfHost(User $user) : string
    {
        $order = $user->orders->first();

        $booking = $order->bookings->first();

        $hostReview = $booking->hostReview;

        $this->setBookingDates($booking);

        $hostReview->update([
            'rating' => NULL,
            'content' => NULL
        ]);

        return $hostReview->id;
    }

    /**
     *  Setup a finished booking and uncompleted review for the user to
     *  leave of a host.
     * 
     *  @param User $user
     *  @return string
     */
    private function setReviewedCompletedBookingOfHost(User $user) : string
    {
        $order = $user->orders->first();

        $booking = $order->bookings->first();

        $hostReview = $booking->hostReview;

        $this->setBookingDates($booking);

        $hostReview->update([
            'rating' => 5,
            'content' => 'This review has been completed.'
        ]);

        return $hostReview->id;
    }

    /**
     *  Set a unfinished booking and uncompleted review for the user
     *  to leave of a host
     * 
     *  @param User $user
     *  @return string
     */
    private function setUnreviewedUncompletedBookingOfHost(User $user) : string
    {
        $order = $user->orders->first();

        $booking = $order->bookings->first();

        $hostReview = $booking->hostReview;

        $this->setBookingDates($booking, [
            'from' => Carbon::now()->addWeeks(2),
            'to' => Carbon::now()->addWeeks(3)
        ]);

        $hostReview->update([
            'rating' => NULL,
            'content' => NULL
        ]);

        return $hostReview->id;
    }

    /**
     *  Set users reviews of renters and return the
     *  RenterReview ids that were changed as a Collection
     * 
     *  @param User $user
     *  @param array $params
     *  @return Illuminate\Support\Collection of RenterReview id's
     */
    private function setUsersReviewsOfRenters(User $user, array $params = []) : Collection
    {
        // Get the users vehicles
        $vehicles = $user->vehicles->pluck('id');

        // Get all the bookings of the users vehicles
        $reviewIds = Booking::whereIn('vehicle_id', $vehicles)
            ->get()
            ->pluck('renter_review_key');

        // Clear all the completed reviews of this host
        RenterReview::whereIn('id', $reviewIds)->update([
            'rating' => $params['rating'] ?? NULL,
            'content' => $params['content'] ?? NULL
        ]);

        return $reviewIds;
    }

    /**
     *  Set the dates of the passed in booking to the past
     *  
     *  @param Booking $booking
     *  @param array $params
     *  @return void
     */
    private function setBookingDates(Booking $booking, array $params = []) : void
    {
        $booking->update([
            'from' => $params['from'] ?? Carbon::now()->subWeeks(4),
            'to' => $params['to'] ?? Carbon::now()->subWeeks(3)
        ]);
    }

    /**
     *  Data for the create review of host request
     * 
     *  @param array $params
     *  @return array
     */
    private function dataForCreateReviewRequest(array $params = []) : array
    {
        return [
            'id' => $params['id'] ?? NULL,
            'rating' => $params['rating'] ?? NULL,
            'content' => $params['content'] ?? NULL
        ];
    }
}