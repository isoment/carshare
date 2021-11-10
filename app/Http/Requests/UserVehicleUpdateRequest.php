<?php

namespace App\Http\Requests;

use App\Models\Vehicle;
use App\Rules\CheckIfSeederImage;
use App\Rules\ExistingFeaturedId;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\Console\Input\Input;

class UserVehicleUpdateRequest extends FormRequest
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
            'images' => ['array', $this->maxImageCount()],
            'images.*.file' => 'image|max:10000',
            'featured_id' => [
                new CheckIfSeederImage, 
                new ExistingFeaturedId($this['images'], $this->route(('id')))
            ],
            'price' => 'required|integer|min:20|max:9999',
            'active' => 'required',
            'description' => 'required|min:10',
        ];
    }

    /**
     *  Custom error messages
     * 
     *  @return array
     */
    public function messages() : array
    {
        return [
            'images.max' => 'Maximum of 12 images allowed, please remove some.'
        ];
    }

    /**
     *  Determine the max image count
     */
    public function maxImageCount() : string
    {
        $vehicle = Vehicle::find($this->route('id'));

        $prevImageCount = $vehicle->vehicleImages->count();

        return 'max:' . 12 - $prevImageCount;
    }
}
