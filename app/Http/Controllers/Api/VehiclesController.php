<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\VehicleIndexRequest;
use App\Http\Resources\VehicleIndexResource;
use App\Http\Resources\VehicleShowResource;
use App\Models\Vehicle;
use App\Services\VehicleService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class VehiclesController extends Controller
{
    protected $vehicleService;

    public function __construct(VehicleService $vehicleService)
    {
        $this->vehicleService = $vehicleService;
    }

    /**
     *  Get a list of vehicles
     * 
     *  @param App\Http\Requests\VehicleIndexRequest
     *  @return Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(VehicleIndexRequest $request) : AnonymousResourceCollection
    {
        return VehicleIndexResource::collection(
            $this->vehicleService->index($request)
        );
    }

    /**
     *  Show an individual vehicle
     * 
     *  @param int $id
     *  @return App\Http\Resources\VehicleShowResource
     */
    public function show(int $id) : VehicleShowResource
    {
        $vehicle = Vehicle::with('vehicleModel.vehicleMake')->findOrFail($id);

        if (!$vehicle->active && auth()->id() !== $vehicle->user_id) {
            return response()->json('Unauthorized', 403);
        }

        return new VehicleShowResource($this->vehicleService->show($vehicle));
    }
}
