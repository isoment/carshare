<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ProfileService {
    /**
     *  Update the authorized users avatar
     * 
     *  @param Illuminate\Http\Request $request
     */
    public function updateUsersAvatar(Request $request) 
    {
        $user = auth()->user();

        $customMessage = ['image' => 'Profile photo must be an image!'];

        $request->validate([
            'image' => 'required|image'
        ], $customMessage);

        $image = $request->file('image');
        $imageName = time() . $user->id . sha1(rand()) . '.jpeg';

        $resize = Image::make($image)
            ->fit(200);

        Storage::disk('public')->put('user-avatars/' . $imageName, $resize->encode('jpg'));

        $user->profile->update([
            'image' => '/storage/user-avatars/' . $imageName
        ]);

        return '/storage/user-avatars/' . $imageName;
    }

    /**
     *  Update the authorized users profile
     * 
     *  @param Illuminate\Http\Request $request
     */
    public function updateUsersProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'location' => 'string|min:2|nullable',
            'languages' => 'string|min:2|nullable',
            'work' => 'string|min:2|nullable',
            'school' => 'string|min:2|nullable',
            'about' => 'string|min:2|nullable'
        ]);

        $user->profile->update([
            'location' => $request->location,
            'languages' => $request->languages,
            'work' => $request->work,
            'school' => $request->school,
            'about' => $request->about
        ]);
    }
}