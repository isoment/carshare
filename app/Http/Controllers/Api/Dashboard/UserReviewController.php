<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserReviewsHostResource;
use Illuminate\Http\Request;

class UserReviewController extends Controller
{
    /**
     *  Get an index of completed reviews by the users of hosts
     */
    public function hostComplete()
    {
        return UserReviewsHostResource::collection(
            current_user()->getCompletedHostReviews()
        );
    }
}
