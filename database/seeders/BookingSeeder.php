<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @param int $min the min number of bookings to create
     * @param int $max the max number of bookings to create
     * @return void
     */
    public static function run(int $min = 1, int $max = 20)
    {
        Vehicle::all()->each(function ($vehicle) use ($min, $max) {
            // The initial booking
            $booking = Booking::factory()->make();

            // Create a collection that we will push bookings to from the loop below
            $bookings = collect([$booking]);

            // We need to create some bookings but they all need to have non
            // overlapping from and to dates. Since a vehicle cannot be booked
            // by different people on the same dates.
            for ($i = 0; $i < random_int($min, $max); $i++) {
                $from = (clone $booking->to)->addDays(random_int(1, 14));

                $to = (clone $from)->addDays(random_int(0, 14));

                $booking = Booking::make([
                    'user_id' => User::where('host', 0)->get()->random()->id,
                    'from' => $from,
                    'to' => $to,
                    'price_total' => random_int(200, 5000)
                ]);

                $bookings->push($booking);
            }

            $vehicle->bookings()->saveMany($bookings);
        });
    }
}
