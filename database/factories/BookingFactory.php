<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\User;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Booking::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $from = Carbon::instance($this->faker->dateTimeBetween('-1 months', '+1 months'));
        $to = (clone $from)->addDays(random_int(0, 14));

        return [
            'user_id' => User::where('host', 0)->get()->random()->id,
            'vehicle_id' => Vehicle::all()->random()->id,
            'from' => $from,
            'to' => $to,
            'price_total' => random_int(200, 5000)
        ];
    }
}
