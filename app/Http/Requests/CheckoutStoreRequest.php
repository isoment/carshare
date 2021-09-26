<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\BookingDatesAvailable;
use App\Rules\VehicleAvailabe;

class CheckoutStoreRequest extends FormRequest
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
        // Need to check that there are items in the cart and then that 
        // the dates given are valid.
        $data = [
            'payment_method_id' => 'required',
            'cart' => 'required|array|min:1',
            'cart.*.vehicle_id' => 'required|exists:vehicles,id',
            'cart.*.dates.start' => 'required|date|after_or_equal:today',
            'cart.*.dates.end' => 'required|date|after_or_equal:cart.*.dates.start'
        ];

        // Check if vehicle is available and user is free to book
        return array_merge($data, [
            'cart.*' => [
                'required', new BookingDatesAvailable, new VehicleAvailabe
            ]
        ]);
    }

    /**
     *  Customize the error messages
     * 
     *  @return array
     */
    public function messages()
    {
        return [
            'cart.*.dates.start.after_or_equal' => 'Start date must be after or equal to now.',
        ];
    }
}
