<?php

namespace Database\Factories;

use App\Models\RenterReview;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class RenterReviewFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = RenterReview::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => Str::uuid(),
            'content' => $this->faker->sentences(5, true),
            'rating' => random_int(1, 5)
        ];
    }
}
