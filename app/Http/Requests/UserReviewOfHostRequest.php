<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserReviewOfHostRequest extends FormRequest
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
            'id' => ['required', 'exists:host_reviews,id'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'content' => ['required', 'string', 'min:5', 'max:250']
        ];
    }

    /**
     *  Custom messages
     * 
     *  @return array
     */
    public function messages()
    {
        return [
            'rating.integer' => 'Invalid rating',
            'rating.min' => 'Invalid rating',
            'rating.max' => 'Invalid rating',
        ];
    }
}
