<?php

namespace Database\Seeders;

use App\Models\HostReview;
use App\Models\RenterReview;
use App\Models\User;
use Illuminate\Database\Seeder;

class SetReviewsToLeave extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::all()->each(function($user) {
            $reviewsOfHosts = $user->getBookings()->pluck('host_review_key');

            $reviewsOfRenters = $user->getVehicleBookings()->pluck('renter_review_key');

            $hostReview = HostReview::whereIn('id', $reviewsOfHosts)
                ->whereNotNull('rating')
                ->first();

            if ($hostReview) {
                $hostReview->update([
                    'rating' => NULL,
                    'content' => NULL
                ]);
            }

            $renterReview = RenterReview::whereIn('id', $reviewsOfRenters)
                ->whereNotNull('rating')
                ->first();

            if ($renterReview) {
                $renterReview->update([
                    'rating' => NULL,
                    'content' => NULL
                ]);
            }
        });
    }
}
