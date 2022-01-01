<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class UserBookingFilterSort implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $types = ['dateAsc', 'dateDesc', 'priceTotalDesc'];

        return in_array($value, $types);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Invalid sort selection.';
    }
}
