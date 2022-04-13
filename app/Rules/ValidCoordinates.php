<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidCoordinates implements Rule
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
        $coordinates = json_decode($value, true);
        $lat = $coordinates['lat'];
        $lng = $coordinates['lng'];

        if ($lat > -90 && $lat < 90 && $lng > -180 && $lng < 180) {
            return true;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The coordinates are invalid';
    }
}
