<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\VehicleMakeListResource;
use App\Models\VehicleMake;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class VehicleInformationController extends Controller
{
    /**
     *  Return an index of vehicle makes
     * 
     *  @return AnonymousResourceCollection
     */
    public function vehicleMakeIndex() : AnonymousResourceCollection
    {
        return VehicleMakeListResource::collection(
            VehicleMake::all()
        );
    }

    /**
     *  Return an index of vehicle models based on the make
     * 
     *  @param Request $request
     */
    public function vehicleModelIndex(Request $request)
    {
        $data = $request->validate([
            'make' => 'required|exists:vehicle_makes,make'
        ]);

        return VehicleMake::vehicleModelsForMake($data['make']);
    }
}
