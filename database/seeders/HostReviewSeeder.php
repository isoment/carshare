<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\HostReview;
use Illuminate\Database\Seeder;

class HostReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * For each user who is a host we want to create some 
     * host reviews. We then attach it to the current user in
     * the each iteration.
     *
     * @return void
     */
    public static function run()
    {
        Booking::all()->each(function ($booking) {

            HostReview::factory()->create([
                'id' => $booking->host_review_key,
                'user_id' => $booking->vehicle->user->id
            ]);
            
        });
    }
}
