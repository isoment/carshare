<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserBookingIndexRequest;
use App\Services\UserBookingService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class UserBookingController extends Controller
{
    protected UserBookingService $userBookingService;

    /**
     *  @param App\Services\UserBookingService
     */
    public function __construct(UserBookingService $userBookingService)
    {
        $this->userBookingService = $userBookingService;
    }

    /**
     *  Get a count of the users total bookings and cancelled bookings
     * 
     *  @return array
     */
    public function showBookingCounts() : array
    {
        return $this->userBookingService->bookingsCount();
    }

    /**
     *  Get a paginated index of users bookings
     * 
     *  @param App\Http\Requests\UserBookingIndexRequest $request
     *  @return LengthAwarePaginator
     */
    public function bookingIndex(UserBookingIndexRequest $request) : LengthAwarePaginator
    {
        Log::info($request->toArray());

        return $this->userBookingService->index($request);
    }
}
