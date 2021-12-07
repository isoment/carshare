<?php

namespace Database\Seeders;

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
            BookingSeeder::class,
            RenterReviewSeeder::class,
            HostReviewSeeder::class,
            CleanupDuplicatedUserBookings::class,
            SetReviewsToLeave::class
        ]);
    }
}
