<?php

declare(strict_types=1);

namespace App\Services;

use App\Http\Requests\UserBookingIndexRequest;
use App\Http\Resources\UserBookingIndexHostResource;
use App\Http\Resources\UserBookingIndexRenterResource;
use App\Http\Resources\UserBookingShowHostResource;
use App\Http\Resources\UserBookingShowRenterResource;
use App\Models\Booking;
use App\Models\Cancellation;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\ApiErrorException;

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

        if ((int) $user->host === 1) {
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
            return UserBookingIndexRenterResource::collection(
                $this->bookingsAsRenter($request, $user)
            );
        }

        if ($request['type'] === 'asHost' && (int) $user->host === 1) {
            return UserBookingIndexHostResource::collection(
                $this->bookingsAsHost($request, $user)
            );
        }

        return response()->json('Error getting bookings', 404);
    }

    /**
     *  Show the information from a booking
     * 
     *  @param int $id
     *  @return JsonResource|JsonResponse
     */
    public function show(int $id) : JsonResource|JsonResponse
    {
        $user = current_user();

        if ($this->userIsHostOfBooking($id, $user)) {
            return new UserBookingShowHostResource($this->showBookingAsHost($id));
        }

        if ($this->userIsRenterOfBooking($id, $user)) {
            return new UserBookingShowRenterResource($this->showBookingAsRenter($id));
        }

        return response()->json('You cannot access this booking', 403);
    }

    /**
     *  Cancel a users booking
     * 
     *  @param int $id
     *  @param Request $request
     *  @return JsonResponse
     */
    public function cancelBooking(int $id, Request $request) : JsonResponse
    {
        $request->validate([
            'reason' => 'required|string|min:10'
        ]);

        $user = current_user();

        $booking = Booking::findOrFail($id);

        if ($booking->demo === 1) {
            return response()->json('You cannot cancel a demo booking', 403);
        }

        if ($booking->hasAlreadyStarted()) {
            return response()->json('You cannot cancel a booking that has started', 403);
        }

        // User is renter
        if ($this->userIsRenterOfBooking($id, $user)) {
            return $this->cancelBookingAsRenter($booking, $request);
        }

        // User is host
        if ($this->userIsHostOfBooking($id, $user)) {
            return $this->cancelBookingAsHost($booking, $request);
        }

        return response()->json('You cannot cancel this booking', 403);
    }

    /**
     *  @param int $id
     *  @return JsonResponse
     */
    public function showRefundAmount(int $id) : JsonResponse
    {
        $user = current_user();

        $booking = Booking::findOrFail($id);

        if ($booking->hasAlreadyStarted()) {
            return response()->json('You cannot cancel a booking that has started', 403);
        }

        if ($this->userIsRenterOfBooking($id, $user)) {
            return response()->json($booking->renterInitiatedRefund());
        }

        if ($this->userIsHostOfBooking($id, $user)) {
            return response()->json([
                'type' => 'Full refund',
                'amount' => $booking->price_total
            ]);
        }

        return response()->json('Unauthorized', 403);
    }

    /**
     *  @param Booking $booking
     *  @return JsonResponse
     */
    private function cancelBookingAsRenter(Booking $booking, Request $request) : JsonResponse
    {
        // Determine refund amount
        $refund = $booking->renterInitiatedRefund();
        $refund['whoCancelled'] = 'renter';
        $refund['reason'] = $request['reason'];

        // Refund renter
        $refundStatus = $this->refundRenter($refund['amount'], $booking);

        if ($refundStatus) {
            $this->createCancellation($booking, $refund);

            $this->updateOrderTotal($booking, $refund['amount']);

            $booking->deleteReviews();

            $booking->delete();

            return response()->json('Booking canceled', 200);
        }

        return response()->json('There was an error cancelling this booking', 404);
    }

    /**
     *  @param Booking
     *  @return JsonResponse
     */
    private function cancelBookingAsHost(Booking $booking, Request $request) : JsonResponse
    {
        $refund = [
            'type' => 'Full refund',
            'amount' => $booking->price_total,
            'whoCancelled' => 'host',
            'reason' => $request['reason']
        ];

        $refundStatus = $this->refundRenter($refund['amount'], $booking);

        if ($refundStatus) {
            $this->createCancellation($booking, $refund);

            $this->updateOrderTotal($booking, $refund['amount']);

            $booking->deleteReviews();

            $booking->delete();

            return response()->json('Booking canceled', 200);
        }
    }

    /**
     *  Refund a users payment method using stripe and returns a
     *  boolean if the refund was successful
     * 
     *  @param string $amount
     *  @param Booking $booking
     */
    private function refundRenter(string $amount, Booking $booking) : bool
    {
        $stripe = new \Stripe\StripeClient(
            env('STRIPE_SECRET')
        );

        $amountAsCents = dollar_format_to_cents($amount);

        $paymentIntentId = $booking->order->payment_intent;

        try {
            $stripe->refunds->create([
                'payment_intent' => $paymentIntentId,
                'amount' => (int) $amountAsCents
            ]);
            return true;
        } catch (ApiErrorException $e) {
            Log::error($e);
            return false;
        }
    }

    /**
     *  Create a new cancellation entry before deleting the booking
     * 
     *  @param Booking $booking
     *  @param array $refund
     *  @return void
     */
    private function createCancellation(Booking $booking, array $refund) : void
    {
        Cancellation::create([
            'vehicle_id' => $booking->vehicle_id,
            'order_id' => $booking->order_id,
            'from' => $booking->from,
            'to' => $booking->to,
            'original_amount' => $booking->price_total,
            'refund_amount' => $refund['amount'],
            'refund_rate' => $refund['type'],
            'who_cancelled' => $refund['whoCancelled'],
            'reason' => $refund['reason']
        ]);
    }

    /**
     *  Update the order total minus the cancelled booking
     * 
     *  @param Booking $booking
     *  @param string $refund
     *  @return void
     */
    private function updateOrderTotal(Booking $booking, string $refund) : void
    {
        $order = $booking->order;
        $newTotal = bcsub($order->total, $refund);
        $order->total = $newTotal;
        $order->save();
    }

    /**
     *  @param int $id
     *  @return Booking
     */
    private function showBookingAsRenter(int $id) : Booking
    {
        return Booking::with('order', 'vehicle.vehicleModel.vehicleMake')
            ->where('id', $id)
            ->first();
    }

    /**
     *  @param int $id
     *  @return Booking
     */
    private function showBookingAsHost(int $id) : Booking
    {
        return Booking::with('vehicle.vehicleModel.vehicleMake')
            ->where('id', $id)
            ->first();
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
                'cancels' => $user->getCancellationsAsRenter()->count()
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
                'cancels' => $user->getCancellationsAsRenter()->count()
            ],
            'asHost' => [
                'bookings' => $user->getVehicleBookings()->count(),
                'cancels' => $user->getCancellationsAsHost()->count()
            ]
        ];
    }
}