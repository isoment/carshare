<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserDetailResource;
use App\Models\User;

class UserDetailController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        if (!auth()->user()) {
            return response()->json('Unauthorized', 403);
        }

        return new UserDetailResource(
            User::where('id', auth()->id())
                ->with('profile')
                ->firstOrFail()
        );
    }
}
