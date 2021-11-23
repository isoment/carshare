<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserReviewOfHostRequest;
use App\Http\Resources\UserReviewsHostResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Log;

class UserReviewController extends Controller
{
    /**
     *  Get a paginated collection of completed reviews by 
     *  the users of hosts
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
     *  the users of hosts
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
     *  @param UserReviewOfHostCreateRequest $request
     */
    public function createReviewOfHost(UserReviewOfHostRequest $request)
    {
        Log::info($request->toArray());
    }
}
