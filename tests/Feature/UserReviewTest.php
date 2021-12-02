<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\HostReview;
use App\Models\RenterReview;
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

        $user = User::has('orders')->where('host', 0)->first();

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

        $user = User::has('orders')->where('host', 0)->first();

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

        $user = User::has('orders')->where('host', 0)->first();

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
    public function a_paginated_index_of_uncompleted_reviews_of_hosts_is_returned()
    {
        $this->createSmallDatabase();

        $user = User::has('orders')->where('host', 0)->first();

        $this->actingAs($user);

        $this->setUnreviewedCompletedBookingOfHost($user);

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

        $user = User::has('orders')->where('host', 0)->first();

        $this->actingAs($user);

        $reviewId = $this->setReviewedCompletedBookingOfHost($user);

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

        $user = User::has('orders')->where('host', 0)->first();

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

        $user = User::has('orders')->where('host', 0)->first();

        $this->actingAs($user);

        $data = $this->dataForCreateReviewRequest([
            'id' => Uuid::uuid4()->toString(),
            'rating' => 5,
            'content' => 'This is valid review text'
        ]);

        $response = $this->json('POST', '/api/dashboard/create-review-of-host', $data);
            
        $response->assertStatus(422)
            ->assertSee('Review id is invalid.');
    }

    /**
     *  @test
     *  The host cannot be reviewed before the booking has ended
     */
    public function the_host_cannot_be_reviewed_before_the_booking_has_ended()
    {
        $this->createSmallDatabase();

        $user = User::has('orders')->where('host', 0)->first();

        $this->actingAs($user);

        $reviewId = $this->setUnreviewedUncompletedBookingOfHost($user);

        $data = $this->dataForCreateReviewRequest([
            'id' => $reviewId,
            'rating' => 5,
            'content' => 'This is valid review text'
        ]);

        $response = $this->json('POST', '/api/dashboard/create-review-of-host', $data);

        $response->assertStatus(422)
            ->assertSee('Booking has not ended, unable to review now.');
    }

    /**
     *  @test
     *  A user cannot leave a review of a host if they did not make the associated booking
     */
    public function user_cannot_review_a_host_if_they_did_not_make_the_associated_booking()
    {
        $this->createSmallDatabase();

        $user = User::has('orders')->where('host', 0)->first();

        $this->actingAs($user);

        // Get a different user for selecting a review
        $userTwo = User::has('orders')->where('id', '!=', $user->id)->first();

        $reviewId = $this->setUnreviewedCompletedBookingOfHost($userTwo);

        $data = $this->dataForCreateReviewRequest([
            'id' => $reviewId,
            'rating' => 5,
            'content' => 'This is valid review text'
        ]);

        $response = $this->json('POST', '/api/dashboard/create-review-of-host', $data);

        $response->assertStatus(403)
            ->assertSee('You are not allowed to review this.');
    }

    /**
     *  @test
     *  A user can leave a review of a host
     */
    public function a_user_can_leave_a_review_of_a_host()
    {
        $this->createSmallDatabase();

        $user = User::has('orders')->where('host', 0)->first();

        $this->actingAs($user);

        $reviewId = $this->setUnreviewedCompletedBookingOfHost($user);

        $data = $this->dataForCreateReviewRequest([
            'id' => $reviewId,
            'rating' => 5,
            'content' => 'This is valid review text'
        ]);

        $response = $this->json('POST', '/api/dashboard/create-review-of-host', $data);

        $response->assertStatus(201)
            ->assertSee('You have left a review.');

        $review = HostReview::find($reviewId);

        $this->assertEquals($review->content, $data['content']);
    }

    /**
     *  @test
     *  Unauthenticated users cannot access the renter-users-reviews-complete api endpoint
     */
    public function unauthenticated_users_cannot_access_the_renter_users_reviews_complete_endpoint()
    {
        $this->json('GET', '/api/dashboard/renter-users-reviews-complete')
            ->assertStatus(401);
    }

    /**
     *  @test
     *  A non host cannot access the renter-users-reviews-complete api endpoint
     */
    public function a_non_host_cannot_access_the_renter_users_reviews_complete_endpoint()
    {
        $this->createSmallDatabase();

        $user = User::has('orders')->where('host', 0)->first();

        $this->actingAs($user);

        $response = $this->json('GET', '/api/dashboard/renter-users-reviews-complete');

        $response->assertStatus(403)
            ->assertSee('You are not a host');
    }

    /**
     *  @test
     *  A paginated index of reviews that the user has left of renters is returned
     */
    public function a_paginated_index_of_reviews_the_user_has_left_of_renters_is_returned()
    {
        $this->createSmallDatabase();

        $user = User::where('host', 1)->first();

        $this->actingAs($user);

        $response = $this->json('GET', '/api/dashboard/renter-users-reviews-complete');

        $response->assertStatus(200)
            ->assertJsonStructure($this->reviewIndexStructure(false));
    }

    /**
     *  @test
     *  A completed review of the renter is seen in the json response
     */
    public function a_completed_review_of_the_renter_is_seen_in_the_json_response()
    {
        $this->createSingleRenterDatabase();

        // We want to ensure that the user we get has a vehicle with bookings.
        $user = User::has('vehicles.bookings')->where('host', 1)->first();

        $this->actingAs($user);

        $changedIds = $this->setUsersReviewsOfRenters($user);

        RenterReview::where('id', $changedIds->first())->update([
            'rating' => 5,
            'content' => 'This is a completed review.'
        ]);

        $response = $this->json('GET', '/api/dashboard/renter-users-reviews-complete');

        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $changedIds->first()])
            ->assertJsonFragment(['content' => 'This is a completed review.']);
    }

    /**
     *  @test
     *  An uncompleted review of the renter is not seen in the json response
     */
    public function an_uncompleted_review_of_the_renter_is_not_seen_in_the_response()
    {
        $this->createSmallDatabase();

        $user = User::has('vehicles.bookings')->where('host', 1)->first();

        $this->actingAs($user);

        $changedIds = $this->setUsersReviewsOfRenters($user);

        $response = $this->json('GET', '/api/dashboard/renter-users-reviews-complete');

        $response->assertJsonMissing(['id' => $changedIds->first()])
            ->assertJsonMissing(['id' => $changedIds->last()]);
    }

    /**
     *  @test
     *  Unauthenticated users cannot access the renter-users-reviews-uncompleted api endpoint
     */
    public function unauthenticated_users_cannot_access_the_renter_users_reviews_uncompleted_endpoint()
    {
        $this->json('GET', '/api/dashboard/renter-users-reviews-uncompleted')
            ->assertStatus(401);
    }

    /**
     *  @test
     *  A non host cannot access the renter-users-reviews-uncompleted api endpoint
     */
    public function a_non_host_cannot_access_the_renter_users_reviews_uncompleted_endpoint()
    {
        $this->createSmallDatabase();

        $user = User::has('orders')->where('host', 0)->first();

        $this->actingAs($user);

        $response = $this->json('GET', '/api/dashboard/renter-users-reviews-uncompleted');

        $response->assertStatus(403)
            ->assertSee('You are not a host');
    }

    /**
     *  @test
     *  A paginated list of reviews that the user has not yet left of renters is returned
     */
    public function a_paginated_index_of_uncompleted_reviews_of_renters_is_returned()
    {
        $this->createSmallDatabase();

        $user = User::has('vehicles.bookings')->where('host', 1)->first();

        $this->actingAs($user);

        $this->setUsersReviewsOfRenters($user);

        $response = $this->json('GET', '/api/dashboard/renter-users-reviews-uncompleted');

        $response->assertStatus(200)
            ->assertJsonStructure($this->reviewIndexStructure(false));
    }

    /**
     *  @test
     *  A completed review of a renter is not seen in the json response
     */
    public function a_completed_review_of_a_renter_is_not_seen_in_the_json_response()
    {
        $this->createSmallDatabase();

        $user = User::has('vehicles.bookings')->where('host', 1)->first();

        $this->actingAs($user);

        $reviewIds = $this->setUsersReviewsOfRenters($user, [
            'rating' => 5,
            'content' => 'Dummy text for some reviews.'
        ]);

        $response = $this->json('GET', '/api/dashboard/renter-users-reviews-uncompleted');

        $response->assertJsonMissing(['id' => $reviewIds->first()])
            ->assertJsonMissing(['id' => $reviewIds->last()]);
    }

    /**
     *  @test
     *  An uncompleted review of a renter is seen in the json response
     */
    public function an_uncompleted_review_of_a_renter_is_seen_in_the_json_response()
    {
        $this->createSmallDatabase();

        $user = User::has('vehicles.bookings')->where('host', 1)->first();

        $this->actingAs($user);

        $reviewIds = $this->setUsersReviewsOfRenters($user, [
            'rating' => 5,
            'content' => 'Dummy text for some reviews.'
        ]);

        RenterReview::where('id', $reviewIds->first())->update([
            'rating' => NULL,
            'content' => NULL
        ]);

        $response = $this->json('GET', '/api/dashboard/renter-users-reviews-uncompleted');

        $response->assertJsonFragment(['id' => $reviewIds->first()]);
    }

    /**
     *  @test
     *  The create review of a renter api endpoint requires user to be authenticated
     */
    public function create_review_of_renter_api_endpoint_required_user_to_be_authenticated()
    {
        $this->json('POST', '/api/dashboard/create-review-of-renter')
            ->assertStatus(401);
    }

    /**
     *  @test
     *  A 422 error is returned if no data is passed into the request to create a review
     *  of a user.
     */
    public function error_422_is_returned_if_no_data_is_passed_to_create_renter_review_request()
    {
        $this->createSmallDatabase();

        $user = User::has('vehicles.bookings')->where('host', 1)->first();

        $this->actingAs($user);

        $response = $this->json('POST', '/api/dashboard/create-review-of-renter');
        
        $response->assertSee('The id field is required.')
            ->assertSee('The rating field is required.')
            ->assertSee('The content field is required.');
    }

    /**
     *  @test
     *  Error 422 is returned if the RenterReview id is invalid
     */
    public function error_422_is_returned_if_renter_review_id_is_invalid()
    {
        $this->createSmallDatabase();

        $user = User::has('vehicles.bookings')->where('host', 1)->first();

        $this->actingAs($user);

        $data = $this->dataForCreateReviewRequest([
            'id' => Uuid::uuid4()->toString(),
            'rating' => 5,
            'content' => 'Some review text here'
        ]);

        $response = $this->json('POST', '/api/dashboard/create-review-of-renter', $data);

        $response->assertStatus(422)
            ->assertSee('Review id is invalid.');
    }

    /**
     *  @test
     *  The renter cannot be reviewed before the booking has ended
     */
    public function the_renter_cannot_be_reviewed_before_the_booking_has_ended()
    {
        $this->createSmallDatabase();

        $user = User::has('vehicles.bookings')->where('host', 1)->first();

        $reviewIds = $this->setUsersReviewsOfRenters($user);

        $this->actingAs($user);

        $booking = Booking::where('renter_review_key', $reviewIds->first())->first();

        $booking->update([
            'from' => Carbon::now()->addWeeks(2),
            'to' => Carbon::now()->addWeeks(3)
        ]);

        $data = $this->dataForCreateReviewRequest([
            'id' => $reviewIds->first(),
            'rating' => 5,
            'content' => 'Some review text here'
        ]);

        $response = $this->json('POST', '/api/dashboard/create-review-of-renter', $data);

        $response->assertStatus(422)
            ->assertSee('Booking has not ended, unable to review now.');
    }

    /**
     *  @test
     *  A user cannot review a renter if the booking is not for their vehicle
     */
    public function a_user_cannot_review_a_renter_if_the_booking_is_not_for_their_vehicle()
    {
        $this->createSmallDatabase();

        $user = User::has('vehicles.bookings')->where('host', 1)->first();

        $this->actingAs($user);

        $vehicleIds = $user->vehicles->pluck('id');

        // We need to get a booking that is not for one of the users vehicle
        $booking = Booking::whereNotIn('vehicle_id', $vehicleIds)->first();

        // Make sure the booking is finished
        $booking->update([
            'from' => Carbon::now()->subWeeks(3),
            'to' => Carbon::now()->subWeeks(2)
        ]);

        $data = [
            'id' => $booking->renter_review_key,
            'rating' => 5,
            'content' => 'Some review text here'
        ];

        $response = $this->json('POST', '/api/dashboard/create-review-of-renter', $data);

        $response->assertStatus(403)
            ->assertSee('You are not allowed to review this.');
    }

    /**
     *  @test
     *  A user can leave a review of a renter
     */
    public function a_user_can_leave_a_review_of_a_renter()
    {
        $this->createSmallDatabase();

        $user = User::has('vehicles.bookings')->where('host', 1)->first();

        $reviewIds = $this->setUsersReviewsOfRenters($user);

        $this->actingAs($user);

        $booking = Booking::where('renter_review_key', $reviewIds->first())->first();

        $booking->update([
            'from' => Carbon::now()->subWeeks(3),
            'to' => Carbon::now()->subWeeks(2)
        ]);

        $data = [
            'id' => $reviewIds->first(),
            'rating' => 5,
            'content' => 'Some review text here'
        ];

        $response = $this->json('POST', '/api/dashboard/create-review-of-renter', $data);

        $response->assertStatus(201)
            ->assertSee('You have left a review.');

        $review = RenterReview::find($reviewIds->first());

        $this->assertEquals($review->content, $data['content']);
    }
}
