<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
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
        $minBirthDate = Carbon::now()->subYears(18)->toFormattedDateString('m/d/Y');

        $messages = [
            'birthdate.before' => 'You must be 18 or older to use carshare',
            'zip' => 'Zip must be 5 numbers'
        ];

        $request->validate([
            'license_image' => 'image|required',
            'license_number' => 'required|string|min:5',
            'state' => ['required', new States],
            'city' => 'required|string',
            'date_issued' => 'required|date|before_or_equal:now',
            'expiration_date' => 'required|date|after:now',
            'birthdate' => ['required', 'date', 'before:' . $minBirthDate],
            'street' => 'required|string|min:5',
            'zip' => 'digits:5'
        ], $messages);

        dd($request->city);
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
