<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserVehicleCreateRequest;
use App\Http\Requests\UserVehicleUpdateRequest;
use Illuminate\Http\Request;
use App\Services\UserVehicleService;

class UserVehicleController extends Controller
{
    public $userVehicleService;

    public function __construct(UserVehicleService $userVehicleService)
    {
        $this->userVehicleService = $userVehicleService;
    }

    /**
     *  An index of the users vehicles
     */
    public function index(Request $request)
    {
        return $this->userVehicleService->index($request);
    }

    /**
     *  Create a new vehicle
     */
    public function create(UserVehicleCreateRequest $request)
    {
        return $this->userVehicleService->create($request);
    }

    /**
     *  Delete a vehicle image
     */
    public function deleteImage(Request $request)
    {
        return $this->userVehicleService->deleteImage($request);
    }

    /**
     *  Update a vehicle
     */
    public function update(int $id, UserVehicleUpdateRequest $request)
    {
        return $this->userVehicleService->update($id, $request);
    }
}
