<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\States;
use Carbon\Carbon;

class DriversLicenseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'license_image' => 'required|image',
            'license_number' => 'required|string|min:5',
            'state' => ['required', new States],
            'date_issued' => 'required|date|before_or_equal:now',
            'expiration_date' => 'required|date|after:now',
            'birthdate' => ['required', 'date', 'before:' . $this->ageCheck()],
            'street' => 'required|string|min:5',
            'zip' => 'digits:5',
            'city' => 'required'
        ];
    }

    /**
     *  Customize the error messages
     * 
     *  @return array
     */
    public function messages()
    {
        return [
            'birthdate.before' => 'You must be 18 or older to use carshare',
            'zip' => 'Zip must be 5 numbers'
        ];
    }

    /**
     *  Get the date to verify users age
     * 
     *  @return string
     */
    public function ageCheck() {
        return Carbon::now()->subYears(18)->toDateString();
    }
}
