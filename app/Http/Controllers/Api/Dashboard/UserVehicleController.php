<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserVehicleCreateRequest;
use App\Http\Resources\UserVehicleIndexResource;
use App\Models\Vehicle;
use App\Models\VehicleImages;
use App\Models\VehicleModel;
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
     *  Create a new vehicle
     */
    public function create(UserVehicleCreateRequest $request)
    {
        $vehicle = Vehicle::create([
            'user_id' => current_user()->id,
            'vehicle_model_id' => VehicleModel::where('model', $request['model'])->first()->id,
            'year' => $request['year'],
            'plate_num' => $request['plate'],
            'price_day' => $request['price'],
            'description' => $request['description'],
            'doors' => $request['doors'],
            'seats' => $request['seats'],
            'active' => 1
        ]);

        // Store the vehicle images
        foreach ($request['images'] as $image) {
            $newName = time() . sha1(random_bytes(10)) . auth()->id() . sha1(random_bytes(8)) . '.' . $image->extension();

            $image->storeAs('vehicle-images', $newName, 'public');

            $fullPath = '/storage/vehicle-images/' . $newName;

            VehicleImages::create([
                'vehicle_id' => $vehicle->id,
                'image' => $fullPath
            ]);
        }

        return response()->json();
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
