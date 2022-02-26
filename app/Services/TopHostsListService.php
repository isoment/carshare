<?php

namespace App\Services;

use App\Models\User;
use App\Models\HostReview;
use Illuminate\Support\Facades\Cache;

class TopHostsListService
{
    /**
     *  Get an index of eight top hosts along with a review and rating that a renter
     *  has left. We also get the renters name and a total count of the reviews that have
     *  been left for the host.
     * 
     *  @return array
     */
    public function index() : array
    {
        $key = 'user:' . current_user()->id . ':top-host-list';

        $topHosts = Cache::store('redis')->remember($key, 86400, function() {
            $randomTopHosts = User::inRandomOrder()
                ->with('profile')
                ->where('top_host', 1)
                ->limit(8)
                ->get();

            $resultsArray = [];

            foreach ($randomTopHosts as $user) {
                $hostReview = HostReview::where('user_id', $user->id)->where('rating', '>=', 3)->first();

                $resultsArray[] = [
                    'host_id' => $user->id,
                    'host_name' => $user->name,
                    'host_avatar' => $user->profile->image,
                    'host_review_count' => HostReview::where('user_id', $user->id)->count(),
                    'host_review' => $hostReview,
                    'renter_name' => $hostReview->booking->order->user->name
                ];
            }

            return $resultsArray;
        });

        return $topHosts;
    }
}