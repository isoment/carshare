<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\VehicleMakeListResource;
use App\Models\VehicleMake;

class VehicleMakeListController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        return VehicleMakeListResource::collection(
            VehicleMake::all()
        );
    }
}
