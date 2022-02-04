<?php

declare(strict_types=1);

namespace App\Services;

use App\Http\Resources\UserBookingIndexRenterResource;
use App\Models\Booking;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class StatisticsService
{
    /**
     *  @return array
     */
    public function showRenterStats() : array
    {
        $user = current_user();
        $usersOrders = $user->orders;
        $bookingsByMonth = $this->bookingsByMonth($usersOrders);
        $totalsByMonth = $this->totalsByMonth($usersOrders);

        return [
            'basic' => $this->basicRenterStats($user, $usersOrders),
            'bookingCountByMonth' => [
                'month' => array_keys($bookingsByMonth),
                'count' => array_values($bookingsByMonth)
            ],
            'totalsByMonth' => [
                'month' => array_keys($totalsByMonth),
                'total' => array_values($totalsByMonth)
            ],
            'recentBookings' => $this->recentBookings($usersOrders)
        ];
    }

    /**
     *  @return array|\Illuminate\Http\JsonResponse
     */
    public function showHostStats() : array|JsonResponse
    {
        $user = current_user();

        if ($user->host === 0) {
            return response()->json('You cannot access this', 403);
        }

        return [
            'basic' => $this->basicHostStats($user),
        ];
    }

    /**
     *  @param User $user
     *  @param \Illuminate\Database\Eloquent\Collection $bookings
     *  @return array
     */
    private function basicHostStats(User $user) : array
    {
        $bookings = $user->getVehicleBookings();
        $bookingLengths = $this->bookingDurations($bookings);

        return [
            'totalEarned' => $bookings->sum('price_total'),
            'bookingCount' => $bookings->count(),
            'cancelCount' => $user->getCancellationsAsHost()->count(),
            'longestBooking' => max($bookingLengths),
            'bookingAverage' => $this->bookingAverage($bookingLengths)
        ];
    }

    /**
     *  @param \Illuminate\Database\Eloquent\Collection $bookings
     *  @return array
     */
    private function bookingDurations(Collection $bookings) : array
    {
        foreach ($bookings as $booking) {
            $arr[] = $booking->bookingTotalDays();
        }

        return $arr;
    }

    /**
     *  @param array $bookingLengths
     *  @return float
     */
    private function bookingAverage(array $bookingLengths) //: float
    {
        $avg = array_sum($bookingLengths) / count($bookingLengths);
        return round($avg, 0, PHP_ROUND_HALF_UP);
    }

    /**
     *  @param User $user
     *  @param \Illuminate\Database\Eloquent\Collection $orders
     *  @return array
     */
    private function basicRenterStats(User $user, Collection $orders) : array
    {
        return [
            'totalSpent' => $orders->sum('total'),
            'bookingCount' => $user->getBookings()->count(),
            'cancelCount' => $user->getCancellationsAsRenter()->count(),
            'orderCount' => $orders->count(),
            'orderAverage' => $orders->average('total')
        ];
    }

    /**
     *  @param \Illuminate\Database\Eloquent\Collection $usersOrders
     *  @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    private function recentBookings(Collection $usersOrders) : AnonymousResourceCollection
    {
        $query = Booking::with('order', 'vehicle.vehicleModel.vehicleMake')
            ->whereIn('order_id', $usersOrders->pluck('id'))
            ->orderBy('created_at', 'DESC')
            ->limit(4)
            ->get();

        return UserBookingIndexRenterResource::collection($query);
    }

    /**
     *  @param \Illuminate\Database\Eloquent\Collection $orders
     */
    private function bookingsByMonth(Collection $orders) : array
    {
        $countByMonth = Booking::whereIn('order_id', $orders->pluck('id'))
            ->whereBetween('from', [Carbon::now()->subMonths(6), Carbon::now()->addMonths(6)])
            ->select(
                DB::raw("DATE_FORMAT(`from`, '%Y-%m') month"),
                DB::raw("count('month') as booking_count")
            )
            ->groupBy('month')
            ->orderBy('month')
            ->limit(8)
            ->get();

        foreach ($countByMonth as $m) {
            $flatArray[Carbon::parse($m['month'])->format('M Y')] = $m['booking_count'];
        }

        return $flatArray;
    }

    /**
     *  @param \Illuminate\Database\Eloquent\Collection
     *  @return array
     */
    private function totalsByMonth(Collection $orders) : array
    {
        $totals = Booking::whereIn('order_id', $orders->pluck('id'))
            ->select(
                DB::raw("DATE_FORMAT(`from`, '%Y-%m') month"),
                DB::raw("sum(price_total) as total")
            )
            ->groupBy('month')
            ->orderBy('total', 'DESC')
            ->limit(4)
            ->get();

        foreach ($totals as $t) {
            $key = Carbon::parse($t['month'])->format('M y') . ' : $' . number_format((float)$t['total'], 2);
            $flatArray[$key] = $t['total'];
        }

        return $flatArray;
    }
}