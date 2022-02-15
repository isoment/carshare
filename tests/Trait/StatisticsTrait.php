<?php

namespace Tests\Trait;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

trait StatisticsTrait
{
    /**
     *  @param \Illuminate\Database\Eloquent\Collection $orders
     *  @return array
     */
    private function bookingsByMonth(Collection $orders) : array
    {
        $countByMonth = Booking::whereIn('order_id', $orders->pluck('id'))
            ->whereBetween('from', [Carbon::now()->subMonths(6), Carbon::now()->addMonths(6)])
            ->select(
                DB::raw('strftime("%Y-%m", `from`) as month'),
                DB::raw("COUNT('month') as booking_count")
            )
            ->groupBy('month')
            ->orderBy('month')
            ->limit(8)
            ->get();

        foreach ($countByMonth as $m) {
            $flatArray[Carbon::parse($m['month'])->format('M Y')] = $m['booking_count'];
        }

        return [
            'bookingCountByMonth' => [
                'month' => array_keys($flatArray),
                'count' => array_values($flatArray)
            ]
        ];
    }

    /**
     *  @param \Illuminate\Database\Eloquent\Collection $collection
     *  @param string $sortColumn
     *  @param int $limit
     *  @param string $key
     *  @return array
     */
    private function totalsByMonth(
        Collection $collection, 
        string $sortColumn = 'order_id', 
        int $limit = 4,
        string $jsonResponseKey = 'totalsByMonth'
    ) : array
    {
        $totals = Booking::whereIn($sortColumn, $collection->pluck('id'))
            ->select(
                DB::raw('strftime("%Y-%m", `from`) as month'),
                DB::raw("SUM(price_total) as total")
            )
            ->groupBy('month')
            ->orderBy('total', 'DESC')
            ->limit($limit)
            ->get();

        foreach ($totals as $t) {
            $key = Carbon::parse($t['month'])->format('M y') . ' : $' . number_format((float)$t['total'], 2);
            $flatArray[$key] = $t['total'];
        }

        return [
            $jsonResponseKey => [
                'month' => array_keys($flatArray),
                'total' => array_values($flatArray)
            ]
        ];
    }
}