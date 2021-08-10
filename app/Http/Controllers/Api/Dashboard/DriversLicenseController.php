<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\DriversLicense;
use App\Rules\States;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DriversLicenseController extends Controller
{
    /**
     *  Create a new drivers license
     *  
     *  @param Illuminate\Http\Request $request
     */
    public function create(Request $request)
    {
        $user = auth()->user();

        $minBirthDate = Carbon::now()->subYears(18)->toDateString();

        $messages = [
            'birthdate.before' => 'You must be 18 or older to use carshare',
            'zip' => 'Zip must be 5 numbers'
        ];

        $request->validate([
            'license_image' => 'required|image',
            'license_number' => 'required|string|min:5',
            'state' => ['required', new States],
            'date_issued' => 'required|date|before_or_equal:now',
            'expiration_date' => 'required|date|after:now',
            'birthdate' => ['required', 'date', 'before:' . $minBirthDate],
            'street' => 'required|string|min:5',
            'zip' => 'digits:5',
            'city' => 'required'
        ], $messages);

        $image = $request->license_image;

        $newName = time() . sha1(random_bytes(10)) . auth()->id() . sha1(random_bytes(8)) . '.' . $image->extension();

        $image->storeAs('license-images', $newName, 'public');

        $fullPath = '/storage/license-images/' . $newName;

        DriversLicense::create([
            'user_id' => $user->id,
            'number' => $request->license_number,
            'state' => $request->state,
            'issued' => $request->date_issued,
            'expiration' => $request->expiration_date,
            'dob' => $request->birthdate,
            'street' => $request->street,
            'city' => $request->city,
            'zip' => $request->zip,
            'license_image' => $fullPath,
        ]);

        return response()->json(['Drivers license submitted'], 201);
    }
    
    /**
     *  Update a users drivers license
     * 
     *  @param Illuminate\Http\Request $request
     */
    public function update(Request $request)
    {

    }

    /**
     *  Show a users license
     */
    public function show()
    {
        
    }
}
