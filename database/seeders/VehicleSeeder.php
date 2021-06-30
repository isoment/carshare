<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @param int $min the min number of vehicles to create
     * @param int $max the max number of vehicles to create
     * @return void
     */
    public static function run(int $min = 1, int $max = 10)
    {
        // If the user is a host create some vehicles
        User::all()->each(function($user) use ($min, $max) {
            if ($user->host) {
                Vehicle::factory()->count(random_int($min, $max))->create([
                    'user_id' => $user->id
                ]);
            }
        });
    }
}
