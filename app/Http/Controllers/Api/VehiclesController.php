<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\VehicleIndexRequest;
use App\Http\Resources\VehicleIndexResource;
use App\Http\Resources\VehicleShowResource;
use App\Models\Vehicle;
use App\Services\VehicleService;

class VehiclesController extends Controller
{
    protected $vehicleService;

    public function __construct(VehicleService $vehicleService)
    {
        $this->vehicleService = $vehicleService;
    }

    /**
     *  Get a list of vehicles
     */
    public function index(VehicleIndexRequest $request)
    {
        return VehicleIndexResource::collection(
            $this->vehicleService->index($request->all())
        );
    }

    /**
     *  Show an individual vehicle
     */
    public function show($id)
    {
        $vehicle = Vehicle::with('vehicleModel.vehicleMake')->findOrFail($id);

        if (!$vehicle->active && auth()->id() !== $vehicle->user_id) {
            return response()->json('Unauthorized', 403);
        }

        return new VehicleShowResource($this->vehicleService->show($vehicle));
    }
}
