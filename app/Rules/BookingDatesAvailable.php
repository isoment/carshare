<?php

namespace App\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class BookingDatesAvailable implements Rule
{
    private $start, $end;

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $this->start = Carbon::parse($value['dates']['start'])->format('m/d/Y');
        $this->end = Carbon::parse($value['dates']['end'])->format('m/d/Y');

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
        return "You already have a booking between {$this->start} and {$this->end}";
    }
}
