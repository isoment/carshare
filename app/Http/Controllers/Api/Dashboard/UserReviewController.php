<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserReviewRequest;
use App\Http\Resources\UserReviewsHostResource;
use App\Http\Resources\UserReviewsRenterResource;
use App\Services\UserReviewService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserReviewController extends Controller
{
    protected $userReviewService;

    /**
     *  @param App\Services\UserReviewService $userReviewService
     */
    public function __construct(UserReviewService $userReviewService)
    {
        $this->userReviewService = $userReviewService;
    }

    /**
     *  Get a paginated collection of completed reviews by 
     *  the user of hosts
     * 
     *  @return Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function ofHostComplete() : AnonymousResourceCollection
    {
        return UserReviewsHostResource::collection(
            current_user()->getCompletedReviewsOfHost()
        );
    }

    /**
     *  Get a paginated collection of completed reviews by 
     *  the user of hosts
     * 
     *  @return Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function ofHostUncompleted() : AnonymousResourceCollection
    {
        return UserReviewsHostResource::collection(
            current_user()->getUncompletedReviewsOfHost()
        );
    }

    /**
     *  Create a review of the host
     * 
     *  @param App\Http\Requests\UserReviewRequest $request
     * 
     *  @return Illuminate\Http\JsonResponse
     */
    public function createReviewOfHost(UserReviewRequest $request) : JsonResponse
    {
        return $this->userReviewService->createReviewOfHost($request);
    }

    /**
     *  Get a paginated collection of renters that the user has reviews
     * 
     *  @return AnonymousResourceCollection|JsonResponse
     */
    public function ofRenterComplete() : AnonymousResourceCollection|JsonResponse
    {
        if ((int)current_user()->host === 0) {
            return response()->json('You are not a host', 403);
        }

        return UserReviewsRenterResource::collection(
            current_user()->getCompletedReviewsOfRenter()
        );
    }

    /**
     *  Get a paginated collection of renters that the user still needs
     *  to review
     * 
     *  @return AnonymousResourceCollection|JsonResponse
     */
    public function ofRenterUncompleted() : AnonymousResourceCollection|JsonResponse
    {
        if ((int)current_user()->host === 0) {
            return response()->json('You are not a host', 403);
        }

        return UserReviewsRenterResource::collection(
            current_user()->getUncompletedReviewsOfRenter()
        );
    }

    /**
     *  Create a review of the renter
     * 
     *  @param App\Http\Requests\UserReviewRequest $request
     * 
     *  @return Illuminate\Http\JsonResponse
     */
    public function createReviewOfRenter(UserReviewRequest $request) : JsonResponse
    {
        return $this->userReviewService->createReviewOfRenter($request);
    }

    /**
     *  Show a users review rating
     */
    public function showReviewRating() : JsonResponse
    {
        return $this->userReviewService->showReviewRating();
    }
}
