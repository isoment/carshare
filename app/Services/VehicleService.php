<?php

namespace App\Services;

use App\Http\Traits\VehicleTrait;
use App\Models\Vehicle;
use App\Models\VehicleImages;
use Carbon\Carbon;

class VehicleService
{
    use VehicleTrait;

    /**
     *  Return and index of vehicles and filter by parameters
     *  in the request.
     */
    public function index($request)
    {
        $from = Carbon::parse($request['from'])->toDateString();
        $to = Carbon::parse($request['to'])->toDateString();

        // Only want to search by price if its selected
        $hasMinMax = isset($request['min']) && isset($request['max']);

        return Vehicle::whereDoesntHave('bookings', function($query) use ($from, $to) {

            $query->betweenDates($from, $to);

        })->when($hasMinMax, function($query) use ($request) {

            $query->whereBetween('price_day', [$request['min'], $request['max']]);

        })->with('vehicleModel.vehicleMake')
            ->with('vehicleImages')
            ->withCount('bookings')
            ->paginate();
    }

    /**
     *  Get information for an individual vehicle
     */
    public function show($id)
    {
        $vehicle = Vehicle::findOrFail($id);

        return collect([
            'vehicle_info' => $vehicle,
            'vehicle_images' => $this->vehicleImages($vehicle->id),
            'host' => $this->hostInfo($vehicle->user_id)
        ]);
    }
}