<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\HostReview;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Trait\UserReviewTrait;
use Tests\Trait\UserTrait;

class UserReviewTest extends TestCase
{
    use RefreshDatabase, UserTrait, UserReviewTrait;

    /**
     *  @test
     *  Unauthenticated users cannot access the host-users-reviews-complete api endpoint
     */
    public function unauthenticated_users_cannot_access_the_host_users_reviews_complete_endpoint()
    {
        $this->json('GET', '/api/dashboard/host-users-reviews-complete')
            ->assertStatus(401);
    }

    /**
     *  @test
     *  A paginated index of reviews that the user has left of hosts is returned
     */
    public function a_paginated_index_of_reviews_the_user_has_left_of_hosts_is_returned()
    {
        $this->createSmallDatabase();

        $user = User::where('host', 0)->first();

        $this->actingAs($user);

        $response = $this->json('GET', '/api/dashboard/host-users-reviews-complete');

        $response->assertStatus(200)
            ->assertJsonStructure($this->reviewIndexStructure());
    }

    /**
     *  @test
     *  A completed review of the host is seen in the json response
     */
    public function a_completed_review_of_the_host_is_seen_in_the_json_response()
    {
        $this->createSingleRenterDatabase();

        $user = User::where('host', 0)->first();

        $this->actingAs($user);

        $order = $user->orders->first();

        $booking = $order->bookings->first();

        $response = $this->json('GET', '/api/dashboard/host-users-reviews-complete');

        $response->assertJsonFragment(['id' => $booking->host_review_key]);
    }

    /**
     *  @test
     *  An incomplete review is not seen in the completed reviews of hosts
     */
    public function an_incomplete_review_is_not_in_the_completed_reviews_of_host_index()
    {
        $this->createSingleRenterDatabase();

        $user = User::where('host', 0)->first();

        $this->actingAs($user);

        $order = $user->orders->first();

        $booking = $order->bookings->first();

        $booking->hostReview->update([
            'rating' => NULL,
            'content' => NULL
        ]);

        $response = $this->json('GET', '/api/dashboard/host-users-reviews-complete');

        $response->assertJsonMissing(['id' => $booking->host_review_key]);
    }

    /**
     *  @test
     *  Unauthenticated users cannot access the host users reviews uncompleted endpoint
     */
    public function unauthenticated_users_cannot_access_the_host_users_reviews_uncompleted_endpoint()
    {
        $this->json('GET', '/api/dashboard/host-users-reviews-uncompleted')
            ->assertStatus(401);
    }

    /**
     *  @test
     *  A paginated list of reviews that the user has not yet left of hosts is returned
     */
    public function a_paginated_list_of_uncompleted_reviews_of_hosts_is_returned()
    {
        $this->createSmallDatabase();

        $user = User::where('host', 0)->first();

        $this->actingAs($user);

        $this->setUnreviewedCompletedBooking($user);

        $response = $this->json('GET', '/api/dashboard/host-users-reviews-uncompleted');

        $response->assertStatus(200)
            ->assertJsonStructure($this->reviewIndexStructure());
    }
}
