<?php

namespace App\Services;

use App\Http\Traits\ReviewTrait;
use App\Http\Traits\VehicleTrait;
use App\Models\Booking;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use App\Http\Requests\VehicleIndexRequest;
use App\Models\VehicleMake;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VehicleService
{
    use VehicleTrait, ReviewTrait;

    /**
     *  Return an index of vehicles and filter by parameters
     *  in the request.
     * 
     *  @param App\Http\Requests\VehicleIndexRequest
     *  @return Illuminate\Pagination\LengthAwarePaginator
     */
    public function index(VehicleIndexRequest $request) : LengthAwarePaginator
    {
        $from = Carbon::parse($request['from'])->toDateString();
        $to = Carbon::parse($request['to'])->toDateString();

        $hasMinMax = isset($request['min']) && isset($request['max']);

        $makeIsSpecified = $this->vehicleMakeIsSpecified($request['make']);

        $orderingByPopularity = $request['orderBy'] === 'popularity';

        return Vehicle::select([
                '*',
                DB::raw('(SELECT count(*) FROM bookings WHERE vehicle_id = vehicles.id) as bookings_count')
            ])->where('active', 1)->whereDoesntHave('bookings', function($query) use ($from, $to) {
                $query->betweenDates($from, $to);
            })->when($hasMinMax, function($query) use ($request) {
                $query->whereBetween('price_day', [$request['min'], $request['max']]);
            })->when($makeIsSpecified, function($query) use($request) {
                $query->whereHas('vehicleModel.vehicleMake', function($query) use ($request) {
                    $query->where('make', $request['make']);
                });
            })
            ->with('vehicleModel.vehicleMake')
            ->with('vehicleImages')
            ->when($orderingByPopularity, function($query) {
                $query->orderByRaw('bookings_count DESC, 1 ASC');
            })->when(!$orderingByPopularity, function($query) use ($request) {
                $query->orderBy(
                    $this->sortColumn($request['orderBy']), 
                    $this->sortDirection($request['orderBy'])
                );
            })->paginate(12);
    }

    /**
     *  Get information for an individual vehicle
     * 
     *  @param Vehicle $vehicle
     *  @return Illuminate\Support\Collection
     */
    public function show(Vehicle $vehicle) : Collection
    {
        return collect($vehicle)->merge([
            'vehicle_images' => $this->vehicleImages($vehicle->id),
            'host' => $this->hostInfo($vehicle->user_id),
            'host_total_trips' => $this->totalTrips($vehicle->user_id),
            'host_rating' => $this->calculateUserTotalRating($vehicle->user_id),
            'vehicle_rating' => Booking::calculateVehicleRating($vehicle->id),
            'vehicle_review_count' => $this->vehiclesReviewCount($vehicle->id),
            'vehicle_trip_count' => $this->vehicleTripCount($vehicle->id),
        ]);
    }

    /**
     *  The user is requesting to filter by a vehicle make
     * 
     *  @param string $makeFromRequest
     *  @return bool
     */
    private function vehicleMakeIsSpecified(string $makeFromRequest) : bool
    {
        // Save a query if user is searching all vehicles
        if ($makeFromRequest === 'all') {
            return false;
        }

        $makesList = VehicleMake::get()->pluck('make');

        if ($makesList->contains(ucwords($makeFromRequest))) {
            return true;
        }

        return false;
    }

    /**
     *  Determine the sort column for the Eloquent orderBy
     * 
     *  @param string $orderBy
     *  @return string
     */
    private function sortColumn(string $orderBy) : string
    {
        if ($orderBy === 'popularity') {
            return 'bookings_count';
        }

        if (str_contains($orderBy, 'price')) {
            return 'price_day';
        }

        return 'price_day';
    }

    /**
     *  Determine the sort direction for the Eloquent orderBy
     * 
     *  @param string $orderBy
     *  @return string
     */
    private function sortDirection(string $orderBy) : string
    {
        if ($orderBy === 'priceLow') {
            return 'asc';
        }

        return 'desc';
    }
}