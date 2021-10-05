<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserVehicleIndexResource;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class UserVehicleController extends Controller
{
    /**
     *  An index of the users vehicles
     */
    public function index(Request $request)
    {
        return UserVehicleIndexResource::collection(
            Vehicle::where('user_id', auth()->id())
                ->with('vehicleModel.vehicleMake')
                ->with('vehicleImages')
                ->paginate(10)
        );
    }
}
