<?php

namespace App\Rules;

use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class VehicleAvailabe implements Rule
{
    private $vehicleYear, $vehicleModel, $start, $end;

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $vehicle = Vehicle::findOrFail($value['vehicle_id']);

        $this->vehicleYear = $vehicle->year;
        $this->vehicleModel = $vehicle->vehicleModel->model;
        $this->start = Carbon::parse($value['dates']['start'])->format('m/d/Y');
        $this->end = Carbon::parse($value['dates']['end'])->format('m/d/Y');

        return $vehicle->isAvailable($value['dates']['start'], $value['dates']['end']);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "The {$this->vehicleYear} {$this->vehicleModel} is not available between {$this->start} and {$this->end}.";
    }
}
