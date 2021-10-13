<?php

namespace App\Rules;

use App\Models\VehicleMake;
use App\Models\VehicleModel as ModelsVehicleModel;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class VehicleModel implements Rule
{
    private string|null $make;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(string|null $make)
    {
        $this->make = $make;
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
        return $this->getModels()->contains($value);
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
     *  Get a collection of vehicle model names based on the make
     */
    public function getModels() : Collection
    {
        $make = VehicleMake::where('make', $this->make)->first();

        return ModelsVehicleModel::where('vehicle_make_id', $make->id)->pluck('model');
    }
}
