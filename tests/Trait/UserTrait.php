<?php

namespace Tests\Trait;

use Database\Seeders\BookingSeeder;
use Database\Seeders\HostReviewSeeder;
use Database\Seeders\RenterReviewSeeder;
use Database\Seeders\Testing\TestingVehicleMakeModelSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\VehicleSeeder;
use App\Models\Booking;
use App\Models\DriversLicense;
use App\Models\Order;
use App\Models\User;

trait UserTrait
{
    /**
     *  Create a small database set using seeders
     */
    private function createSmallDatabase()
    {
        UserSeeder::run(20, 12, 4);
        TestingVehicleMakeModelSeeder::run();
        VehicleSeeder::run(1, 2);
        BookingSeeder::run(5, 7);
        RenterReviewSeeder::run();
        HostReviewSeeder::run();
    }

    /**
     *  Create a database with a single renter and a single host and
     *  1 to 2 bookings for each.
     */
    private function createSingleRenterDatabase()
    {
        UserSeeder::run(1, 1, 0);
        TestingVehicleMakeModelSeeder::run();
        VehicleSeeder::run(1, 2);
        BookingSeeder::run(1, 1);
        RenterReviewSeeder::run();
        HostReviewSeeder::run();
    }

    private function deleteAllUsersBookingsAndOrders(User $user) : void
    {
        $usersOrders = $user->orders->pluck('id');

        Booking::whereIn('order_id', $usersOrders)->each(function($booking) {
            $booking->delete();
        });

        Order::destroy(collect($usersOrders));
    }

    /**
     *  Authorize user to drive by creating a drivers license entry
     * 
     *  @param User $user
     */
    private function authorizeUserToDrive(User $user) : void
    {
        DriversLicense::factory()->create(['user_id' => $user->id]);
    }
}