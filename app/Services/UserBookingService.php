<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserBookingService
{
    /**
     *  A paginated index of users bookings
     * 
     *  @param Request $request
     */
    public function index(Request $request)
    {
        $user = current_user();

        if ($request['type'] === 'asHost') {
            return $this->bookingsAsHost($request, $user);
        } else {
            return $this->bookingsAsRenter($request, $user);
        }
    }

    /**
     *  Index of bookings as renter
     */
    private function bookingsAsRenter($request, $user)
    {
        return Booking::with('order')
            ->whereIn('order_id', $user->orders->pluck('id'))
            ->paginate(4);
    }

    /**
     *  Index of bookings as host
     */
    private function bookingsAsHost($request, $user)
    {
        return Booking::whereIn('vehicle_id', $user->vehicles->pluck('id'))
            ->paginate(4);
    }

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
                'bookings' => $user->getBookings()->count(),
                'cancels' => rand(1,5)
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
                'bookings' => $user->getBookings()->count(),
                'cancels' => rand(1,5)
            ],
            'asHost' => [
                'bookings' => $user->getVehicleBookings()->count(),
                'cancels' => rand(1,5)
            ]
        ];
    }
}