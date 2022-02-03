<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Booking;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class StatisticsService
{
    public function showRenterStats()
    {
        $user = current_user();
        $usersOrders = $user->orders;
        $bookingsByMonth = $this->bookingsByMonth($usersOrders);

        return [
            'basic' => $this->basicStats($user, $usersOrders),
            'bookingCountByMonth' => [
                'month' => array_keys($bookingsByMonth),
                'count' => array_values($bookingsByMonth)
            ]
        ];
    }

    /**
     *  @param User $user
     *  @param Collection $orders
     *  @return array
     */
    public function basicStats(User $user, Collection $orders) : array
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
     *  @param Collection $orders
     */
    public function bookingsByMonth(Collection $orders) : array
    {
        $countByMonth = Booking::whereIn('order_id', $orders->pluck('id'))
            ->whereBetween('from', [Carbon::now()->subMonths(3), Carbon::now()->addMonths(4)])
            ->select(
                DB::raw("DATE_FORMAT(`from`, '%Y-%m') month"),
                DB::raw("count('month') as booking_count")
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $flatArr = [];

        foreach ($countByMonth as $m) {
            $flatArray[ Carbon::parse($m['month'])->format('M')] = $m['booking_count'];
        }

        return $flatArray;
    }

    /**
     *  @param Collection $orders
     */
    public function bookingsByMonthWithNulls(Collection $orders)
    {
        return DB::select('
            SELECT `from`,
                   `to`,
                   price_total
            FROM bookings
            LEFT
        ');
    }
}