<?php

declare(strict_types=1);

namespace App\Services;

use App\Http\Resources\UserBookingIndexResource;
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

        $vehicles = $user->vehicles;
        $highestBookedMonths = $this->hostHighestBookedMonths($vehicles);
        $bookingDurations = $this->hostDurationOfBookings($user);
        $earningsByMonth = $this->totalsByMonth($vehicles, 'vehicle_id', 5);
        $popularVehicles = $this->hostsPopularVehicles($vehicles);

        return [
            'basic' => $this->basicHostStats($user),
            'highestBookedMonths' => [
                'month' => array_keys($highestBookedMonths),
                'count' => array_values($highestBookedMonths)
            ],
            'durationOfBookings' => [
                'booking' => array_keys($bookingDurations),
                'duration' => array_values($bookingDurations)
            ],
            'earningsByMonth' => [
                'month' => array_keys($earningsByMonth),
                'total' => array_values($earningsByMonth)
            ],
            'popularVehicles' => [
                'vehicle' => array_keys($popularVehicles),
                'count' => array_values($popularVehicles)
            ],
            'recentBookings' => $this->recentBookings($vehicles, 'vehicle_id')
        ];
    }

    /**
     *  @param Collection $vehicles
     *  @return array
     */
    private function hostsPopularVehicles(Collection $vehicles) : array
    {
        foreach ($vehicles as $vehicle) {
            $id = $vehicle->id;
            $year = $vehicle->year;
            $model = $vehicle->vehicleModel->model;

            $array["{$year} {$model} {$id}"] = $vehicle->bookings->count(); 
        }

        return array_splice($array, -5, 5);
    }

    /**
     *  @param User $user
     *  @param array
     */
    private function hostDurationOfBookings(User $user) : array
    {
        $bookings = $user->getVehicleBookings();

        return collect($this->bookingDurations($bookings))
            ->take(-14)
            ->toArray();
    }

    /**
     *  @param \Illuminate\Database\Eloquent\Collection $vehicles
     *  @param array
     */
    private function hostHighestBookedMonths(Collection $vehicles) : array
    {
        $busiestMonths = Booking::whereIn('vehicle_id', $vehicles->pluck('id'))
            ->select(
                DB::raw("DATE_FORMAT(`from`, '%Y-%m') as month"),
                DB::raw("COUNT('month') as booking_count")
            )
            ->groupBy('month')
            ->orderBy('booking_count', 'DESC')
            ->limit(8)
            ->get();

        foreach ($busiestMonths as $m) {
            $key = Carbon::parse($m['month'])->format('M Y');
            $flatArray[$key] = $m['booking_count'];
        }

        return $flatArray;
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
            $arr[$booking['id']] = $booking->bookingTotalDays();
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
     *  @param \Illuminate\Database\Eloquent\Collection $collection
     *  @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    private function recentBookings(
        Collection $collection, 
        string $sortColumn = 'order_id'
    ) : AnonymousResourceCollection
    {
        $builder = Booking::with('vehicle.vehicleModel.vehicleMake')
            ->whereIn($sortColumn, $collection->pluck('id'))
            ->orderBy('created_at', 'DESC')
            ->limit(4)
            ->get();

        return UserBookingIndexResource::collection($builder);
    }

    /**
     *  @param \Illuminate\Database\Eloquent\Collection $orders
     */
    private function bookingsByMonth(Collection $orders) : array
    {
        $countByMonth = Booking::whereIn('order_id', $orders->pluck('id'))
            ->whereBetween('from', [Carbon::now()->subMonths(6), Carbon::now()->addMonths(6)])
            ->select(
                DB::raw("DATE_FORMAT(`from`, '%Y-%m') as month"),
                DB::raw("COUNT('month') as booking_count")
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
     *  @param \Illuminate\Database\Eloquent\Collection $collection
     *  @param string $sortColumn
     *  @param int $limit
     *  @return array
     */
    private function totalsByMonth(
        Collection $collection, 
        string $sortColumn = 'order_id', 
        int $limit = 4
    ) : array
    {
        $totals = Booking::whereIn($sortColumn, $collection->pluck('id'))
            ->select(
                DB::raw("DATE_FORMAT(`from`, '%Y-%m') as month"),
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

        return $flatArray;
    }
}