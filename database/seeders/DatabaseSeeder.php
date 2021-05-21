<?php

namespace Database\Seeders;

use App\Models\Vehicle;
use Database\Factories\VehicleModelFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            VehicleMakeSeeder::class,
            VehicleModelSeeder::class,
            VehicleSeeder::class,
        ]);
    }
}
