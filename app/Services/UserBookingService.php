<?php

declare(strict_types=1);

namespace App\Services;

use App\Http\Requests\UserBookingIndexRequest;
use App\Http\Resources\UserBookingIndexHostResource;
use App\Http\Resources\UserBookingsIndexRenterResource;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

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
     *  A paginated index of users bookings
     * 
     *  @param App\Http\Requests\UserBookingIndexRequest $request
     *  @return JsonResource|JsonResponse
     */
    public function index(UserBookingIndexRequest $request) : JsonResource|JsonResponse
    {
        $user = current_user();

        if ($request['type'] === 'asRenter') {
            return UserBookingsIndexRenterResource::collection(
                $this->bookingsAsRenter($request, $user)
            );
        }

        if ($request['type'] === 'asHost' && $user->host === 1) {
            return UserBookingIndexHostResource::collection(
                $this->bookingsAsHost($request, $user)
            );
        }

        return response()->json(['Error getting bookings'], 404);
    }

    /**
     *  Show the information from a booking
     * 
     *  @param int $id
     */
    public function show(int $id)
    {
        $user = current_user();

        if ($this->userIsHostOfBooking($id, $user)) {
            return $this->showBookingAsHost($id);
        }

        if ($this->userIsRenterOfBooking($id, $user)) {
            return $this->showBookingAsRenter($id);
        }

        return response()->json(['You cannot access this booking'], 403);
    }

    /**
     *  @param int $id
     *  @return Collection
     */
    public function showBookingAsRenter(int $id) : Collection
    {
        return Booking::with('order', 'vehicle.vehicleModel.vehicleMake')
            ->where('id', $id)
            ->get();
    }

    /**
     *  @param int $id
     *  @return Collection
     */
    public function showBookingAsHost(int $id) : Collection
    {
        return Booking::with('vehicle.vehicleModel.vehicleMake')
            ->where('id', $id)
            ->get();
    }

    /**
     *  @param int $bookingId
     *  @param User $user
     */
    private function userIsHostOfBooking(int $bookingId, User $user) : bool
    {
        $usersBookings = $user->getVehicleBookings()->pluck('id');

        return $usersBookings->contains($bookingId);
    }

    /**
     *  @param int $bookingId
     *  @param User $user
     */
    private function userIsRenterOfBooking(int $bookingId, User $user) : bool
    {
        $usersBookings = $user->getBookings()->pluck('id');

        return $usersBookings->contains($bookingId);
    }

    /**
     *  The query to run to get an index of the users bookings as a renter
     * 
     *  @return Illuminate\Pagination\LengthAwarePaginator
     */
    private function bookingsAsRenter(UserBookingIndexRequest $request, User $user) : LengthAwarePaginator
    {
        return Booking::with('order', 'vehicle.vehicleModel.vehicleMake')
            ->whereIn('order_id', $user->orders->pluck('id'))
            ->where('to', '>=', $request['from'])
            ->where('from', '<=', $request['to'])
            ->orderBy($this->sortColumn($request), $this->sortDirection($request))
            ->paginate(4);
    }

    /**
     *  The query to run to get an index of the users bookings as a host
     * 
     *  @return Illuminate\Pagination\LengthAwarePaginator
     */
    private function bookingsAsHost(UserBookingIndexRequest $request, User $user) : LengthAwarePaginator
    {
        return Booking::with('vehicle.vehicleModel.vehicleMake')
            ->whereIn('vehicle_id', $user->vehicles->pluck('id'))
            ->where('to', '>=', $request['from'])
            ->where('from', '<=', $request['to'])
            ->orderBy($this->sortColumn($request), $this->sortDirection($request))
            ->paginate(4);
    }

    /**
     *  The column to sort the index query by
     * 
     *  @param UserBookingIndexRequest $user
     *  @return string
     */
    private function sortColumn(UserBookingIndexRequest $request) : string
    {
        if ($request['sort'] === 'dateAsc' || $request['sort'] === 'dateDesc') {
            return 'to';
        }

        if ($request['sort'] === 'priceTotalDesc') {
            return 'price_total';
        }
    }

    /**
     *  The direction to sort results, pass Desc into the query parameter string
     *  to sort in descending order.
     * 
     *  @param UserBookingIndexRequest $user
     *  @return string
     */
    private function sortDirection(UserBookingIndexRequest $request) : string
    {
        if (str_contains($request['sort'], 'Desc')) {
            return 'DESC';
        }

        return 'ASC';
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