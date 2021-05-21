<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class VehicleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Vehicle::class;

    /**
     *  Get a year for a vehicle
     */
    public function vehicleYear()
    {
        $currentYear = Carbon::now()->year;

        $startYear = Carbon::now()->subYears(10)->year;

        return rand($startYear, $currentYear);
    }

    /**
     *  Generate a plate number
     */
    public function plateNumber()
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $letterPrefix = substr(str_shuffle($chars), 0, 2);

        return $letterPrefix . rand(11111, 999999);
    }

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::all()->random()->id,
            'vehicle_model_id' => VehicleModel::all()->random()->id,
            'year' => $this->vehicleYear(),
            'plate_num' => $this->plateNumber(),
            'price_day' => $this->faker->randomFloat(2, 35, 500),
        ];
    }
}
