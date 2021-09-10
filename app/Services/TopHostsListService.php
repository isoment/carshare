<?php

namespace App\Services;

use App\Models\User;
use App\Models\HostReview;

class TopHostsListService
{
    /**
     *  Get an index of eight top hosts along with a review and rating that a renter
     *  has left. We also get the renters name and a total count of the reviews that have
     *  been left for the host.
     * 
     *  @return array
     */
    public function index()
    {
        $collection = User::with('profile')
            ->where('top_host', 1)
            ->join('host_reviews', 'users.id', '=', 'host_reviews.user_id')
            ->where('rating', '>=', 3)
            ->get(['users.*', 'host_reviews.id as review_id', 'host_reviews.rating', 'host_reviews.content']);

        // Get one review with the user info collection
        $unique = $collection->unique('id')->shuffle()->take(8);

        // We want to add the number of host reviews and also
        // the renters name for the review we selected above for the top host
        $hostReviewInfo = [];

        foreach ($unique as $u) {
            $hostReview = HostReview::where('id', $u['review_id'])->first();

            $hostReviewToAdd = [
                'host_review_count' => HostReview::where('user_id', $u['id'])->count(),
                'renter_name' => $hostReview->booking->order->user->name
            ];

            array_push($hostReviewInfo, $hostReviewToAdd);
        }

        // For each entry in the $unique array we need to create a new array
        // with the below information.
        $finalResult = array();
        $count = count($unique);

        for ($i = 0; $i < $count; $i++) {
            $finalResult[$i]['id'] = $unique[$i]['id'];
            $finalResult[$i]['host_name'] = $unique[$i]['name'];
            $finalResult[$i]['host_avatar'] = $unique[$i]['profile']['image'];
            $finalResult[$i]['created_at'] = $unique[$i]['created_at'];
            $finalResult[$i]['rating'] = $unique[$i]['rating'];
            $finalResult[$i]['content'] = $unique[$i]['content'];
            $finalResult[$i]['review_id'] = $unique[$i]['review_id'];
            $finalResult[$i]['host_review_count'] = $hostReviewInfo[$i]['host_review_count'];
            $finalResult[$i]['renter_name'] = $hostReviewInfo[$i]['renter_name'];
        }

        return $finalResult;
    }
}