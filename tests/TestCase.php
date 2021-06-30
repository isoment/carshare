<?php

namespace Tests;

use App\Models\User;
use Database\Seeders\BookingSeeder;
use Database\Seeders\HostReviewSeeder;
use Database\Seeders\RenterReviewSeeder;
use Database\Seeders\Testing\TestingVehicleMakeModelSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\VehicleSeeder;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     *  Create a small database set using seeders
     */
    public function createSmallDatabase()
    {
        UserSeeder::run(20, 12, 4);
        TestingVehicleMakeModelSeeder::run();
        VehicleSeeder::run(1, 2);
        BookingSeeder::run(1, 2);
        RenterReviewSeeder::run();
        HostReviewSeeder::run();
    }
}
