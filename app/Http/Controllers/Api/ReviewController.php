<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReviewVehicleCollection;
use App\Models\Vehicle;
use App\Services\ReviewService;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    protected $reviewService;

    public function __construct(ReviewService $reviewService)
    {
        $this->reviewService = $reviewService;
    }

    /**
     *  Get a paginated index of reviews for a vehicle
     * 
     *  @param int $id
     */
    public function vehicleReviews(int $id)
    {
        $vehicleId = Vehicle::findOrFail($id)->id;

        return new ReviewVehicleCollection(
            $this->reviewService->vehicleReviews($vehicleId)
        );
    }
}
