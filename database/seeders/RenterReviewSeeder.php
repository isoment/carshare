<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\RenterReview;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class RenterReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * For each user who is a renter we want to create some 
     * renter reviews. We then attach it to the current user in
     * the each iteration.
     *
     * @return void
     */
    public static function run()
    {
        Booking::all()->each(function ($booking) {

            $currentDate = Carbon::now();
            $bookingEnd = Carbon::parse($booking->to);

            if ($currentDate->isBefore($bookingEnd)) {
                RenterReview::factory()->create([
                    'id' => $booking->renter_review_key,
                    'user_id' => $booking->order->user_id,
                    'rating' => NULL,
                    'content' => NULL
                ]);
            } else {
                RenterReview::factory()->create([
                    'id' => $booking->renter_review_key,
                    'user_id' => $booking->order->user_id,
                ]);
            }

        });
    }
}
