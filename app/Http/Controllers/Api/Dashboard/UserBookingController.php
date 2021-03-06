<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserBookingIndexRequest;
use App\Services\UserBookingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
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
     *  @return JsonResource|JsonResponse
     */
    public function bookingIndex(UserBookingIndexRequest $request) : JsonResource|JsonResponse
    {
        return $this->userBookingService->index($request);
    }

    /**
     *  @param int $id
     *  @return JsonResponse
     */
    public function showBookingRefund(int $id) : JsonResponse
    {
        return $this->userBookingService->showRefundAmount($id);
    }

    /**
     *  @param int $id
     *  @return JsonResource|JsonResponse
     */
    public function bookingShow(int $id) : JsonResource|JsonResponse
    {
        return $this->userBookingService->show($id);
    }

    /**
     *  @param int $id
     */
    public function bookingDelete(int $id, Request $request)
    {
        return $this->userBookingService->cancelBooking($id, $request);
    }
}
