<?php

namespace App\Services;

use App\Http\Requests\UserReviewOfHostRequest;
use App\Models\HostReview;

class UserReviewService
{
    /**
     *  Create a review of a host
     *  
     *  @param App\Http\Requests\UserReviewOfHostRequest $request
     */
    public function createReviewOfHost(UserReviewOfHostRequest $request)
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
}