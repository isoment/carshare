<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\UserBookingService;
use Illuminate\Http\Request;

class UserBookingController extends Controller
{
    protected UserBookingService $userBookingService;

    public function __construct(UserBookingService $userBookingService)
    {
        $this->userBookingService = $userBookingService;
    }

    /**
     *  Get a count of the users total bookings and cancelled bookings
     */
    public function showBookingCounts()
    {
        return $this->userBookingService->bookingsCount();
    }
}
