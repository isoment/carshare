<?php

declare(strict_types=1);

namespace App\Services;

use App\Http\Resources\UserBookingIndexRenterResource;
use App\Models\Booking;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class StatisticsService
{
    /**
     *  @param array
     */
    public function showRenterStats() : array
    {
        $user = current_user();
        $usersOrders = $user->orders;
        $bookingsByMonth = $this->bookingsByMonth($usersOrders);
        $totalsByMonth = $this->totalsByMonth($usersOrders);

        return [
            'basic' => $this->basicStats($user, $usersOrders),
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
     *  @param User $user
     *  @param \Illuminate\Database\Eloquent\Collection $orders
     *  @return array
     */
    private function basicStats(User $user, Collection $orders) : array
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