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
     * @return void
     */
    public function run()
    {
        // If the user is a host create some vehicles
        User::all()->each(function($user) {
            if ($user->host) {
                Vehicle::factory()->count(random_int(1,10))->create([
                    'user_id' => $user->id
                ]);
            }
        });
    }
}
