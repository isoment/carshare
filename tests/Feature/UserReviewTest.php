<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\HostReview;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Ramsey\Uuid\Uuid;
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

    /**
     *  @test
     *  A completed review is not seen in the uncompleted reviews of hosts
     */
    public function a_completed_review_is_not_seen_in_the_uncompleted_reviews_of_hosts()
    {
        $this->createSmallDatabase();

        $user = User::where('host', 0)->first();

        $this->actingAs($user);

        $reviewId = $this->setReviewedCompletedBooking($user);

        $response = $this->json('GET', '/api/dashboard/host-users-reviews-uncompleted');

        $response->assertJsonMissing(['id' => $reviewId]);
    }

    /**
     *  @test
     *  The create review of a host api endpoint requires user to be authenticated
     */
    public function create_review_of_host_api_endpoint_required_user_to_be_authenticated()
    {
        $this->json('POST', '/api/dashboard/create-review-of-host')
            ->assertStatus(401);
    }

    /**
     *  @test
     *  Error 422 is returned if no data is passed in request
     */
    public function error_422_is_returned_if_no_data_is_passed_in_request()
    {
        $this->createSmallDatabase();

        $user = User::where('host', 0)->first();

        $this->actingAs($user);

        $response = $this->json('POST', '/api/dashboard/create-review-of-host');

        $response->assertStatus(422)
            ->assertSee('The id field is required.')
            ->assertSee('The rating field is required.')
            ->assertSee('The content field is required.');
    }

    /**
     *  @test
     *  Error 422 is returned if the host review id is invalid
     */
    public function error_422_is_returned_if_host_review_id_is_invalid()
    {
        $this->createSmallDatabase();

        $user = User::where('host', 0)->first();

        $this->actingAs($user);

        $data = $this->dataForCreateReviewOfHostRequest([
            'id' => Uuid::uuid4()->toString(),
            'rating' => 5,
            'content' => 'This is valid review text'
        ]);

        $response = $this->json('POST', '/api/dashboard/create-review-of-host', $data);
            
        $response->assertStatus(422)
            ->assertSee('Review id is invalid.');
    }

    /**
     * @test
     * The host cannot be reviewed before the booking has ended
     */
    public function the_host_cannot_be_reviewed_before_the_booking_has_ended()
    {
        $this->createSmallDatabase();

        $user = User::where('host', 0)->first();

        $this->actingAs($user);

        $reviewId = $this->setUnreviewedUncompletedBooking($user);

        $data = $this->dataForCreateReviewOfHostRequest([
            'id' => $reviewId,
            'rating' => 5,
            'content' => 'This is valid review text'
        ]);

        $response = $this->json('POST', '/api/dashboard/create-review-of-host', $data);

        $response->assertStatus(422)
            ->assertSee('Booking has not ended, unable to review now.');
    }
}
