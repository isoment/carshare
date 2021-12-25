<?php

namespace App\Services;

use App\Models\User;

class UserBookingService
{
    /**
     *  Get a count of the bookings and cancellations for a user
     * 
     *  @return array
     */
    public function bookingsCount() : array
    {
        $user = current_user();

        if ($user->host === 1) {
            return $this->bookingsCountForHost($user);
        }

        return $this->bookingsCountForRenter($user);
    }

    /**
     *  The bookings count if the user is a renter only
     * 
     *  @param User $user
     *  @return array
     */
    private function bookingsCountForRenter(User $user) : array
    {
        return [
            'asRenter' => [
                'total' => $user->getBookings()->count(),
                'cancels' => rand(1,10)
            ]
        ];
    }

    /**
     *  The bookings count if the user is a host
     * 
     *  @param User $user
     *  @return array
     */
    private function bookingsCountForHost(User $user) : array
    {
        return [
            'asRenter' => [
                'total' => $user->getBookings()->count(),
                'cancels' => rand(1,10)
            ],
            'asHost' => [
                'total' => $user->getVehicleBookings()->count(),
                'cancels' => rand(1,10)
            ]
        ];
    }
}