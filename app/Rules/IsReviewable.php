<?php

namespace App\Rules;

use App\Models\HostReview;
use App\Models\RenterReview;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class IsReviewable implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value) : bool
    {
        $review = HostReview::find($value) ?? RenterReview::find($value);

        // Sometimes this rule may be called before validating the review
        // key, if this happens the above returns null, so it should fail.
        if ($review === null) {
            return false;
        }

        return Carbon::parse($review->booking->to)->isBefore(Carbon::now());
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message() : string
    {
        return 'Booking has not ended, unable to review now.';
    }
}
