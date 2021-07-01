<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\VehicleIndexRequest;
use App\Http\Resources\VehicleIndexResource;
use App\Services\VehicleService;

class VehiclesController extends Controller
{
    protected $vehicleService;

    public function __construct(VehicleService $vehicleService)
    {
        $this->vehicleService = $vehicleService;
    }

    public function index(VehicleIndexRequest $request)
    {
        return VehicleIndexResource::collection(
            $this->vehicleService->index($request->all())
        );
    }
}
