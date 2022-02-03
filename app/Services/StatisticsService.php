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
            ]
        ];
    }

    /**
     *  @param User $user
     *  @param Collection $orders
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
     *  @param Collection $orders
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

    private function totalsByMonth(Collection $orders)
    {
        $totals = Booking::whereIn('order_id', $orders->pluck('id'))
            ->select(
                DB::raw("DATE_FORMAT(`from`, '%Y-%m') month"),
                DB::raw("sum(price_total) as total")
            )
            ->groupBy('month')
            ->orderBy('total', 'DESC')
            ->limit(6)
            ->get();

        foreach ($totals as $t) {
            $flatArray[Carbon::parse($t['month'])->format('M y')] = $t['total'];
        }

        return $flatArray;
    }
}