<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CheckIfSeederImage implements Rule
{
    /**
     * If the featured image string contains 'seeder' string
     * it cannot be changed
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return !str_contains($value, 'seeder');
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Cannot use this image for featured image';
    }
}
