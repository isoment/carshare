<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
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

    public function updateProfile()
    {
        $user = auth()->user();
    }
}
