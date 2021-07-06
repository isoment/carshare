<?php

namespace Database\Factories;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'admin' => false,
            'host' => false,
            'top_host' => false
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }

    /**
     *  After creating a user create a profile
     */
    public function configure()
    {
        return $this->afterCreating(function(User $user) {
            Profile::create([
                'user_id' => $user->id,
                'phone' => '555-555-5555',
                'image' => '/storage/avatar-seeder-img/' . $this->imagePicker()
            ]);
        });
    }

    /**
     *  Randomly pick an avatar image
     */
    public function imagePicker()
    {
        $images = [
            '01.jpg', '03.jpg', '05.jpg', '07.jpg', '09.jpg', '11.jpg', '13.jpg', '16.jpg',
            '15.jpg', '17.jpg', '19.jpg', '21.jpg', '23.jpg', '25.jpg', '27.jpg', '29.jpg',
            '02.jpg', '04.jpg', '06.jpg', '08.jpg', '10.jpg', '12.jpg', '14.jpg', '30.jpg',
            '18.jpg', '20.jpg', '22.jpg', '24.jpg', '26.jpg', '28.jpg' 
        ];

        return $images[array_rand($images)];
    }
}
