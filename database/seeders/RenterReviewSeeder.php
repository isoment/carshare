<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\HostReview;
use App\Models\RenterReview;
use App\Models\User;
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
    public function run()
    {
        // User::where('host', 0)->get()->each(function($user) {
        //     $reviews = RenterReview::factory()->count(random_int(5, 30))->make();
        //     $user->renterReviews()->saveMany($reviews);
        // });

        Booking::all()->each(function ($booking) {

            RenterReview::factory()->create([
                'id' => $booking->renter_review_key,
                'user_id' => User::where('host', 0)->get()->random()
            ]);
            
        });
    }
}
