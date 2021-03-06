<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::where('host', 0)->get()->random()->id,
            'payment_method' => uniqid('id_', true),
            'payment_intent' => uniqid('id_', true),
            'total' => $this->faker->randomFloat(0, 100, 9999)
        ];
    }
}
