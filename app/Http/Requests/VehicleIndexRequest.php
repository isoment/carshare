<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VehicleIndexRequest extends FormRequest
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
            'from' => ['required', 'date', 'after_or_equal:yesterday'],
            'to' => ['required', 'date', 'after_or_equal:from'],
            'min' => ['numeric'],
            'max' => ['numeric', 'gte:min']
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
            'required' => 'Field required',
            'date' => 'Must be a date',
            'from.after_or_equal' => 'Date must be after or equal to now',
            'to.after_or_equal' => 'Date must be after or equal to from date',
            'gte:min' => 'Max must be greater than min'
        ];
    }
}
