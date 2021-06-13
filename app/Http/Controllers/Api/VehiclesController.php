<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\VehicleIndexResource;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;

class VehiclesController extends Controller
{
    public function index(Request $request)
    {
        $customMessages = [
            'required' => 'Field required',
            'date' => 'Must be a date',
            'from.after_or_equal' => 'Date must be after or equal to now',
            'to.after_or_equal' => 'Date must be after or equal to from date',
            'gte:min' => 'Max must be greater than min'
        ];

        $data = $request->validate([
            'from' => ['required', 'date', 'after_or_equal:yesterday'],
            'to' => ['required', 'date', 'after_or_equal:from'],
            'min' => ['numeric'],
            'max' => ['numeric', 'gte:min']
        ], $customMessages);

        return VehicleIndexResource::collection(
            Vehicle::indexOfAvailableVehicles($data)
        );
    }
}
