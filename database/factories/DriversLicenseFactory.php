<?php

namespace Database\Factories;

use App\Models\DriversLicense;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class DriversLicenseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DriversLicense::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'number' => uniqid('pm_' . true),
            'state' => $this->faker->state,
            'issued' => Carbon::now()->subYears(random_int(2,5))->format('Y-m-d'),
            'expiration' => Carbon::now()->addYears(random_int(3,5))->format('Y-m-d'),
            'dob' => Carbon::now()->subYears(random_int(22,40))->format('Y-m-d'),
            'street' => $this->faker->streetAddress,
            'city' => $this->faker->city,
            'zip' => $this->faker->postcode,
            'license_image' => NULL,
            'verified' => 1
        ];
    }
}
