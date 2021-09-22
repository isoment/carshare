<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class BookingDatesAvailable implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return auth()->user()->hasNoBooking(
            $value['dates']['start'], 
            $value['dates']['end']
        );
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'You already have a booking on these dates.';
    }
}
