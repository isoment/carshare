<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReviewsFromRenterCollection;
use App\Http\Resources\ReviewsFromHostCollection;
use App\Http\Resources\ReviewVehicleCollection;
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
     *  A paginated index of reviews for a vehicle
     * 
     *  @param int $vehicleId
     */
    public function vehicleReviews(int $vehicleId)
    {
        return new ReviewVehicleCollection(
            $this->reviewService->vehicleReviews($vehicleId)
        );
    }

    /**
     *  A paginated index of a users reviews from hosts
     * 
     *  @param int $userId
     */
    public function reviewsFromHosts(int $userId)
    {
        return new ReviewsFromHostCollection(
            $this->reviewService->reviewsFromHosts($userId)
        );
    }

    /**
     *  A paginated index of a users reviews from renters
     * 
     *  @param int $userId
     */
    public function reviewsFromRenters(int $userId)
    {
        return new ReviewsFromRenterCollection(
            $this->reviewService->reviewsFromRenters($userId)
        );
    }
}
