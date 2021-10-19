<?php

namespace App\Http\Requests;

use App\Rules\VehicleMake;
use App\Rules\VehicleModel;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class UserVehicleCreateRequest extends FormRequest
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
            'images' => 'required|array|min:1',
            'images.*.file' => 'image|max:10000',
            'featured_id' => 'required',
            'make' => ['required', new VehicleMake],
            'model' => ['required', new VehicleModel($this['make'])],
            'year' => ['required', 'integer', 'min:1945', $this->twoYears()],
            'plate' => 'required|max:10',
            'seats' => 'required',
            'doors' => 'required',
            'price' => 'required|integer|min:1|max:9999',
            'description' => 'required|min:10'
        ];
    }

    /**
     *  Customer error messages
     * 
     *  @return array
     */
    public function messages() : array
    {
        return [
            'featured_id.required' => 'Please select a featured image'
        ];
    }

    /**
     *  Two years from now for validating vehicles
     */
    private function twoYears() : string
    {
        Log::info(Carbon::now()->addYears(2)->format('Y'));

        return 'max:' . Carbon::now()->addYears(2)->format('Y');
    }
}
