<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Order;
use App\Models\User;
use App\Models\Vehicle;
use Carbon\Carbon;
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
    public static function run(int $min = 6, int $max = 22)
    {
        Vehicle::all()->each(function ($vehicle) use ($min, $max) {
            // The initial booking
            $booking = Booking::factory()->make([
                'from' => Carbon::today()->subDays(rand(40,60)),
                'price_day' => $vehicle->price_day,
                'demo' => 1
            ]);

            // Set the end date for the initial booking
            $booking['to'] = Carbon::parse($booking['from'])->addDays(rand(1,5));

            $dayCount = Carbon::parse($booking['from'])
                ->diffInDays($booking['to']) + 1;

            // Set the price total for the initial booking
            $booking['price_total'] = $dayCount * $vehicle->price_day;

            // Create a collection that we will push bookings to from the loop below
            $bookings = collect([$booking]);

            // We need to create some bookings but they all need to have non
            // overlapping from and to dates. Since a vehicle cannot be booked
            // by different people on the same dates.
            for ($i = 0; $i < random_int($min, $max); $i++) {
                $from = (clone $booking->to)->addDays(random_int(1, 14));

                $to = (clone $from)->addDays(random_int(0, 14));

                $days = Carbon::parse($from)->diffInDays($to) + 1;

                $booking = Booking::make([
                    'order_id' => Order::factory()->create([
                        'user_id' => User::get()->random()->id,
                        'total' => $days * $vehicle->price_day
                    ])->id,
                    'from' => $from,
                    'to' => $to,
                    'price_total' => $days * $vehicle->price_day,
                    'price_day' => $vehicle->price_day,
                    'demo' => 1
                ]);

                $bookings->push($booking);
            }

            $vehicle->bookings()->saveMany($bookings);
        });
    }
}
