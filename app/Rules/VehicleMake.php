<?php

namespace App\Rules;

use App\Models\VehicleMake as ModelsVehicleMake;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Collection;

class VehicleMake implements Rule
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
        return $this->vehicleMakes()->contains($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Make is invalid.';
    }

    /**
     *  Get an array of vehicle makes
     * 
     *  @return array
     */
    public function vehicleMakes() : Collection
    {
        return ModelsVehicleMake::get()->pluck('make');
    }
}
