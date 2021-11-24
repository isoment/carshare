<?php

namespace App\Rules;

use App\Models\HostReview;
use App\Models\RenterReview;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class IsReviewable implements Rule
{
    private string $id;

    /**
     * @param string $id
     * @return void
     */
    public function __construct(string $id)
    {
        $this->id = $id;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value) : bool
    {
        $review = HostReview::find($this->id) ?? RenterReview::find($this->id);

        return Carbon::parse($review->booking->to)->isBefore(Carbon::now());
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message() : string
    {
        return 'Booking has not ended, unable to review now';
    }
}
