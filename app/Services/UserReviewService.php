<?php

namespace App\Services;

use App\Http\Requests\UserReviewRequest;
use App\Models\HostReview;
use App\Models\RenterReview;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class UserReviewService
{
    /**
     *  Create a review of a host
     *  
     *  @param App\Http\Requests\UserReviewRequest $request
     * 
     *  @return Illuminate\Http\JsonResponse
     */
    public function createReviewOfHost(UserReviewRequest $request) : JsonResponse
    {
        $review = HostReview::find($request['id']);

        // Check that the user is allowed to review this
        if (!$review->userCanLeaveReview()) {
            return response()->json('You are not allowed to review this', 403);
        }

        $review->update([
            'rating' => $request['rating'],
            'content' => $request['content']
        ]);

        return response()->json('You have left a review', 201);
    }

    /**
     *  Create a review of a renter
     * 
     *  @param App\Http\Requests\UserReviewRequest $request
     * 
     *  @return Illuminate\Http\JsonResponse
     */
    public function createReviewOfRenter(UserReviewRequest $request) : JsonResponse
    {
        if (current_user()->host === 0) {
            return response()->json('You are not a host', 403);
        }

        $review = RenterReview::find($request['id']);

        // Check that the user is allowed to review this
        if (!$review->userCanLeaveReview()) {
            return response()->json('You are not allowed to review this', 403);
        }

        $review->update([
            'rating' => $request['rating'],
            'content' => $request['content']
        ]);

        return response()->json('You have left a review', 201);
    }
}