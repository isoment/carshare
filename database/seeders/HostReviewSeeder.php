<?php

namespace Database\Seeders;

use App\Models\HostReview;
use App\Models\User;
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
    public function run()
    {
        User::where('host', 1)->get()->each(function($user) {
            $reviews = HostReview::factory()->count(random_int(5, 30))->make();
            $user->hostReviews()->saveMany($reviews);
        });
    }
}
