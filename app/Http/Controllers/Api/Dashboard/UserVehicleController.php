<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserVehicleIndexResource;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserVehicleController extends Controller
{
    /**
     *  An index of the users vehicles
     */
    public function index(Request $request)
    {
        return UserVehicleIndexResource::collection(
            Vehicle::where('user_id', auth()->id())
                ->where('active', $this->isActive($request->active))
                ->orderBy('price_day', $this->priceSortDirection($request->priceSort))
                ->with('vehicleModel.vehicleMake')
                ->with('vehicleImages')
                ->paginate(10)
        );
    }

    /**
     *  Determine active
     * 
     *  @param string $status
     * 
     *  @return boolean
     */
    public function isActive(string $status) : bool
    {
        return $status === 'true' ? 1 : 0;
    }

    /**
     *  Determine sort direction for price
     *  
     *  @param string $direction
     * 
     *  @return string
     */
    public function priceSortDirection(string $direction) : string
    {
        if (!in_array($direction, array('desc', 'asc'))) {
            return 'desc';
        }

        return $direction;
    }
}
