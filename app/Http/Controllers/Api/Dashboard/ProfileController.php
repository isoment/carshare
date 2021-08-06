<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\ProfileService;
use Illuminate\Http\Request;

class ProfileController extends Controller
{   
    protected $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    /**
     *  Update the avatar
     * 
     *  @param Illuminate\Http\Request $request
     */
    public function updateAvatar(Request $request)
    {
        $imageName = $this->profileService->updateUsersAvatar($request);

        return response()->json($imageName, 200);
    }

    /**
     *  Update the authorized users profile
     * 
     *  @param Illuminate\Http\Request $request
     */
    public function updateProfile(Request $request)
    {
        $this->profileService->updateUsersProfile($request);

        return response()->json([], 204);
    }
}
