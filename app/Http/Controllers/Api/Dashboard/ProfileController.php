<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{   
    /**
     *  Update the authorized users avatar
     * 
     *  @param Illuminate\Http\Request $request
     */
    public function updateAvatar(Request $request)
    {
        $user = User::findOrFail(auth()->id());

        $customMessage = ['image' => 'Only image allowed'];

        $request->validate([
            'image' => 'required|image'
        ], $customMessage);

        $image = $request->file('image');

        $filePath = $image->store('user-avatars', 'public');

        $user->profile->update([
            'image' => '/storage/' . $filePath
        ]);

        return response()->json('/storage/' . $filePath, 200);
    }

    /**
     *  Update the authorized users profile
     * 
     *  @param Illuminate\Http\Request $request
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'location' => 'string|nullable',
            'languages' => 'string|nullable',
            'work' => 'string|nullable',
            'school' => 'string|nullable',
            'about' => 'string|nullable'
        ]);

        $user->profile->update([
            'location' => $request->location,
            'languages' => $request->languages,
            'work' => $request->work,
            'school' => $request->school,
            'about' => $request->about
        ]);

        return response()->json([], 204);
    }
}
