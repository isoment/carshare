<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ProfileController extends Controller
{   
    /**
     *  Update the authorized users avatar
     * 
     *  @param Illuminate\Http\Request $request
     */
    public function updateAvatar(Request $request)
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

        return response()->json('/storage/user-avatars/' . $imageName, 200);
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
