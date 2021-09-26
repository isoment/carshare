<?php

namespace Database\Factories;

use App\Models\DriversLicense;
use App\Models\User;
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
            'issued' => $this->faker->date('Y-m-d', 'now'),
            'expiration' => $this->faker->date('Y-m-d', '+2 years'),
            'dob' => $this->faker->date('Y-m-d', '-30 years'),
            'street' => $this->faker->streetAddress,
            'city' => $this->faker->city,
            'zip' => $this->faker->postcode,
            'license_image' => 'dummy',
            'verified' => 0
        ];
    }
}
