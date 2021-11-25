<?php

namespace App\Rules;

use App\Models\HostReview;
use App\Models\RenterReview;
use Illuminate\Contracts\Validation\Rule;

class IsValidReview implements Rule
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
        $hostReview = HostReview::find($value);
        $renterReview = RenterReview::find($value);

        if ($hostReview || $renterReview) {
            return true;
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Review id is invalid.';
    }
}
