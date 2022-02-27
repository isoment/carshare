<?php

namespace App\Services;

use App\Http\Traits\CacheModeTrait;
use App\Models\User;
use App\Models\HostReview;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class TopHostsListService
{
    use CacheModeTrait;

    /**
     *  Get an index of eight top hosts along with a review and rating that a renter
     *  has left. We also get the renters name and a total count of the reviews that have
     *  been left for the host.
     * 
     *  @return array
     */
    public function index() : array
    {
        if (current_user()) {
            $cacheKey = $this->setCacheMode(
                'user:' . current_user()->id . ':top-host-list',
                'user:test-auth:top-host-list'
            );

            $topHosts = Cache::store('redis')->remember($cacheKey, 86400, function() {
                return $this->topHostsListQuery();
            });
    
            return $topHosts;
        }

        if (auth()->guest()) {
            $cacheKey = $this->setCacheMode(
                'user:guest-' . Session::getId() . ':top-host-list',
                'user:test-guest:top-host-list'
            );

            $topHostsGuest = Cache::store('redis')->remember($cacheKey, 86400, function() {
                return $this->topHostsListQuery();
            });
    
            return $topHostsGuest;
        }
    }

    private function topHostsListQuery() : array
    {
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
    }
}