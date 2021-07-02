<?php

namespace App\Http\Traits;

use App\Models\User;
use App\Models\VehicleImages;

trait VehicleTrait
{
    /**
     *  Get the vehicle images for associated vehicle
     * 
     *  @param int $vehicleId
     */
    public function vehicleImages($vehicleId)
    {
        return VehicleImages::where('vehicle_id', $vehicleId)->get()->pluck('image');
    }

    /**
     *  Get the host info of a vehicle
     * 
     *  @param int $vehicleId
     */
    public function hostInfo($userId)
    {
        return User::find($userId);
    }

}