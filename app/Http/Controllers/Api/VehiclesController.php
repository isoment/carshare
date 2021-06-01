<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\VehicleIndexResource;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehiclesController extends Controller
{
    public function index()
    {
        return VehicleIndexResource::collection(
            Vehicle::allVehicles()
        );
    }
}
