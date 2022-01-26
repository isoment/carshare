<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Cancellation;
use App\Models\DriversLicense;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Trait\CheckoutTrait;
use Tests\Trait\UserBookingTrait;
use Tests\Trait\UserTrait;

class UserBookingTest extends TestCase
{
    use RefreshDatabase, UserTrait, UserBookingTrait, CheckoutTrait;

    /**
     *  @test
     *  Unauthenticated users cannot access the user booking index
     */
    public function unauthenticated_users_cannot_access_the_user_booking_index()
    {
        $this->json('GET', '/api/dashboard/booking-index')
            ->assertStatus(401);
    }

    /**
     *  @test
     *  A paginated index of users bookings as a renter is returned
     */
    public function a_paginated_index_of_users_bookings_as_a_renter_is_returned()
    {
        $this->createSmallDatabase();

        $user = User::has('orders')->where('host', 0)->first();

        $this->actingAs($user);

        $data = $this->validIndexQueryParams();

        $this->json('GET', '/api/dashboard/booking-index', $data)
            ->assertStatus(200)
            ->assertJsonStructure($this->bookingIndexStructure());
    }

    /**
     *  @test
     *  422 is returned if the booking type is invalid
     */
    public function error_422_is_returned_if_the_booking_type_is_invalid()
    {
        $this->createSmallDatabase();

        $user = User::has('orders')->where('host', 0)->first();

        $this->actingAs($user);

        $data = $this->validIndexQueryParams(['type' => 'abc123']);

        $this->json('GET', '/api/dashboard/booking-index', $data)
            ->assertStatus(422)
            ->assertSee('Invalid booking type');
    }

    /**
     *  @test
     *  422 is returned if the sort is invalid
     */
    public function error_422_is_returned_if_the_filter_sort_is_invalid()
    {
        $this->createSmallDatabase();

        $user = User::has('orders')->where('host', 0)->first();

        $this->actingAs($user);

        $data = $this->validIndexQueryParams(['sort' => 'abc123']);

        $this->json('GET', '/api/dashboard/booking-index', $data)
            ->assertStatus(422)
            ->assertSee('Invalid sort selection.');
    }

    /**
     *  @test
     *  The index contains the renters booking
     */
    public function the_user_booking_index_contains_the_renters_booking()
    {
        $this->createSmallDatabase();

        $user = User::has('orders')->where('host', 0)->first();

        $this->actingAs($user);

        $data = $this->validIndexQueryParams();

        $this->json('GET', '/api/dashboard/booking-index', $data)
            ->assertStatus(200);

        $orders = $user->orders->pluck('id');

        $booking = Booking::whereIn('order_id', $orders)
            ->orderBy('to', 'ASC')
            ->first();

        $this->json('GET', '/api/dashboard/booking-index', $data)
            ->assertJsonFragment(['id' => $booking->id])
            ->assertSee(Carbon::parse($booking->from)->toDateString())
            ->assertSee(Carbon::parse($booking->to)->toDateString());
    }

    /**
     *  @test
     *  The user booking index does not contain other users bookings
     */
    public function the_users_booking_index_does_not_include_other_users_bookings()
    {
        $this->createSmallDatabase();

        $user = User::has('orders')->where('host', 0)->first();

        $this->actingAs($user);

        $this->deleteAllUsersBookingsAndOrders($user);

        $data = $this->validIndexQueryParams();

        $response = $this->json('GET', '/api/dashboard/booking-index', $data)
            ->assertStatus(200);

        $user2 = User::has('orders')->inRandomOrder()->where('id', '!=', $user->id)->first();

        $booking = $user2->getBookings()->first();

        $response->assertJsonMissing(['id' => $booking->id])
            ->assertDontSee(Carbon::parse($booking->from)->toDateString())
            ->assertDontSee(Carbon::parse($booking->to)->toDateString());
    }

    /**
     *  @test
     *  Bookings of users vehicles are shown in host bookings index
     */
    public function bookings_of_users_vehicles_are_shown_in_host_bookings_index()
    {
        $this->createSmallDatabase();

        $user = User::has('vehicles')->where('host', 1)->first();

        $this->actingAs($user);

        $data = $this->validIndexQueryParams(['type' => 'asHost']);

        $this->json('GET', '/api/dashboard/booking-index', $data)
            ->assertStatus(200);

        $vehicles = $user->vehicles->pluck('id');

        $booking = Booking::whereIn('vehicle_id', $vehicles)
            ->orderBy('to', 'ASC')
            ->first();

        $response = $this->json('GET', '/api/dashboard/booking-index', $data)
            ->assertJsonFragment(['id' => $booking->id])
            ->assertSee(Carbon::parse($booking->from)->toDateString())
            ->assertSee(Carbon::parse($booking->to)->toDateString());
    }

    /**
     *  @test
     *  An unauthenticated user cannot access the booking count endpoint
     */
    public function an_unauthenticated_user_cannot_access_the_booking_count_endpoint()
    {
        $this->json('GET', '/api/dashboard/booking-counts')
            ->assertStatus(401);
    }

    /**
     *  @test
     *  The json structure from the booking count endpoint is correct for renters
     */
    public function the_json_structure_from_the_booking_count_endpoint_is_correct_for_renters()
    {
        $this->createSmallDatabase();

        $user = User::has('orders')->where('host', 0)->first();

        $this->actingAs($user);

        $this->json('GET', '/api/dashboard/booking-counts')
            ->assertStatus(200)
            ->assertJsonStructure([
                'asRenter' => ['bookings', 'cancels']
            ]);
    }

    /**
     *  @test
     *  The json structure from the booking count endpoint is correct for hosts
     */
    public function the_json_structure_from_the_booking_count_endpoint_is_correct_for_hosts()
    {
        $this->createSmallDatabase();

        $user = User::has('orders')->where('host', 1)->first();

        $this->actingAs($user);

        $this->json('GET', '/api/dashboard/booking-counts')
            ->assertStatus(200)
            ->assertJsonStructure([
                'asRenter' => ['bookings', 'cancels'],
                'asHost' => ['bookings', 'cancels']
            ]);
    }

    /**
     *  @test
     *  The correct booking counts are displayed
     */
    public function the_correct_booking_counts_are_displayed()
    {
        $this->createSmallDatabase();

        $user = User::has('orders')->where('host', 1)->first();

        $this->actingAs($user);

        $this->json('GET', '/api/dashboard/booking-counts')
            ->assertJsonFragment([
                'asRenter' => [
                    'bookings' => $user->getBookings()->count(), 
                    'cancels' => $user->getCancellationsAsRenter()->count()
                ],
            ])
            ->assertJsonFragment([
                'asHost' => [
                    'bookings' => $user->getVehicleBookings()->count(), 
                    'cancels' => $user->getCancellationsAsHost()->count()
                ],
            ]);
    }

    /**
     *  @test
     *  An unauthenticated user cannot access the bookings show api endpoint
     */
    public function an_unauthenticated_user_cannot_access_the_booking_show_api_endpoint()
    {
        $this->json('GET', '/api/dashboard/show-booking/1')
            ->assertStatus(401);
    }

    /**
     *  @test
     *  The renter can see the booking for their rental
     */
    public function the_renter_can_see_the_booking_for_their_rental()
    {
        $this->createSmallDatabase();

        $user = User::where('host', 0)->first();

        $this->actingAs($user);

        $booking = $user->getBookings()->first();

        $this->json('GET', "/api/dashboard/show-booking/{$booking->id}")
            ->assertStatus(200)
            ->assertSimilarJson([
                'data' => [
                    'userIs' => 'renter',
                    'hasStarted' => $booking->hasAlreadyStarted(),
                    'booking' => [
                        'id' => $booking->id,
                        'from' => Carbon::parse($booking->from)->toDateTimeString(),
                        'to' => Carbon::parse($booking->to)->toDateTimeString(),
                        'price_day' => $booking->price_day,
                        'price_total' => $booking->price_total,
                        'created_at' => $booking->created_at
                    ],
                    'vehicle' => [
                        'id' => $booking->vehicle->id,
                        'image' => $booking->vehicle->featured_image,
                        'make' => $booking->vehicle->vehicleModel->vehicleMake->make,
                        'model' => $booking->vehicle->vehicleModel->model,
                        'year' => $booking->vehicle->year,
                        'created_at' => $booking->vehicle->created_at
                    ],
                    'order' => [
                        'id' => $booking->order->id,
                        'total' => $booking->order->total,
                        'transaction_id' => $booking->order->payment_method,
                        'created_at' => $booking->order->created_at
                    ],
                    'user' => [
                        'id' => $booking->vehicle->user->id,
                        'name' => $booking->vehicle->user->name,
                        'image' => $booking->vehicle->user->profile->image,
                        'location' => $booking->vehicle->user->profile->location,
                        'languages' => $booking->vehicle->user->profile->languages,
                        'work' => $booking->vehicle->user->profile->work,
                        'school' => $booking->vehicle->user->profile->school,
                        'about' => $booking->vehicle->user->profile->about,
                        'created_at' => $booking->vehicle->user->created_at
                    ]
                ]
            ]);
    }

    /**
     *  @test
     *  The host can see the booking for their vehicle
     */
    public function the_host_can_see_the_booking_for_their_vehicle()
    {
        $this->createSmallDatabase();

        $booking = Booking::inRandomOrder()->first();

        $user = $booking->vehicle->user;

        $this->actingAs($user);

        $this->json('GET', "/api/dashboard/show-booking/{$booking->id}")
            ->assertStatus(200)
            ->assertSimilarJson([
                'data' => [
                    'userIs' => 'host',
                    'hasStarted' => $booking->hasAlreadyStarted(),
                    'booking' => [
                        'id' => $booking->id,
                        'from' => $booking->from,
                        'to' => $booking->to,
                        'price_day' => $booking->price_day,
                        'price_total' => $booking->price_total,
                        'created_at' => $booking->created_at
                    ],
                    'vehicle' => [
                        'id' => $booking->vehicle->id,
                        'image' => $booking->vehicle->featured_image,
                        'make' => $booking->vehicle->vehicleModel->vehicleMake->make,
                        'model' => $booking->vehicle->vehicleModel->model,
                        'year' => $booking->vehicle->year,
                    ],
                    'user' => [
                        'id' => $booking->order->user->id,
                        'name' => $booking->order->user->name,
                        'image' => $booking->order->user->profile->image,
                        'location' => $booking->order->user->profile->location,
                        'languages' => $booking->order->user->profile->languages,
                        'work' => $booking->order->user->profile->work,
                        'school' => $booking->order->user->profile->school,
                        'about' => $booking->order->user->profile->about,
                        'created_at' => $booking->order->user->created_at
                    ]
                ],
            ]);
    }

    /**
     *  @test
     *  A user who is not a host or renter cannot view that booking
     */
    public function a_user_who_is_not_a_host_or_renter_cannot_view_that_booking()
    {
        $this->createSmallDatabase();

        $user = User::factory()->create();

        $this->actingAs($user);

        $booking = Booking::inRandomOrder()->first();

        $this->json('GET', "/api/dashboard/show-booking/{$booking->id}")
            ->assertStatus(403)
            ->assertSee('You cannot access this booking');
    }

    /**
     *  @test
     *  An unauthenticated user cannot access the booking refund amount endpoint
     */
    public function an_unauthenticated_user_cannot_access_the_booking_refund_amount_endpoint()
    {
        $this->json('GET', '/api/dashboard/booking-refund-amount/1')
            ->assertStatus(401);
    }

    /**
     *  @test
     *  A 403 response is returned if the booking has already started
     */
    public function a_403_response_is_returned_if_the_booking_has_already_started_at_booking_refund_amount_api()
    {
        $this->createSmallDatabase();

        $booking = Booking::where('from', '<=', Carbon::now())->first();

        $user = $booking->order->user;

        $this->actingAs($user);

        $this->json('GET', "/api/dashboard/booking-refund-amount/{$booking->id}")
            ->assertStatus(403)
            ->assertSee('You cannot cancel a booking that has started');
    }

    /**
     *  @test
     *  A 403 response is returned if the user is not the host or renter
     */
    public function a_403_response_is_returned_at_booking_refund_amount_api_if_the_user_is_not_the_host_or_renter()
    {
        $this->createSmallDatabase();

        $booking = $booking = Booking::inRandomOrder()
            ->where('from', '>', Carbon::now())
            ->first();

        $user = User::factory()->create();

        $this->actingAs($user);

        $this->json('GET', "/api/dashboard/booking-refund-amount/{$booking->id}")
            ->assertStatus(403)
            ->assertSee('Unauthorized');
    }

    /**
     *  @test
     *  The host can get the refund amount for the booking from the api endpoint
     */
    public function the_host_can_get_the_refund_amount_for_the_booking_from_the_api_endpoint()
    {
        $this->createSmallDatabase();

        $booking = $booking = Booking::inRandomOrder()
            ->where('from', '>', Carbon::now())
            ->first();

        $user = $booking->vehicle->user;

        $this->actingAs($user);

        $this->json('GET', "/api/dashboard/booking-refund-amount/{$booking->id}")
            ->assertStatus(200)
            ->assertSimilarJson([
                'type' => 'Full refund',
                'amount' => $booking->price_total
            ]);
    }

    /**
     *  @test
     *  The host can get the refund amount for the booking from the api endpoint
     */
    public function the_renter_can_get_the_refund_amount_for_the_booking_from_the_api_endpoint()
    {
        $this->createSmallDatabase();

        $booking = $booking = Booking::inRandomOrder()
            ->where('from', '>', Carbon::now())
            ->first();

        $user = $booking->order->user;

        $this->actingAs($user);

        $this->json('GET', "/api/dashboard/booking-refund-amount/{$booking->id}")
            ->assertStatus(200)
            ->assertSimilarJson($booking->renterInitiatedRefund());
    }

    /**
     *  @test
     *  An unauthenticated user cannot access the booking delete endpoint
     */
    public function an_unauthenticated_user_cannot_access_the_booking_delete_endpoint()
    {
        $this->json('DELETE', '/api/dashboard/booking-delete/1')
            ->assertStatus(401);
    }

    /**
     *  @test
     *  A reason must be included when cancelling a booking
     */
    public function a_reason_must_be_included_when_cancelling_a_booking()
    {
        $this->createSmallDatabase();

        $user = User::inRandomOrder()->first();

        $this->actingAs($user);

        $this->json('DELETE', '/api/dashboard/booking-delete/1')
            ->assertStatus(422)
            ->assertSee('The reason field is required');
    }

    /**
     *  @test
     *  A booking that has already started cannot be cancelled
     */
    public function a_booking_that_has_already_started_cannot_be_cancelled()
    {
        $this->createSmallDatabase();

        $booking = Booking::where('from', '<=', Carbon::now())->first();

        $user = $booking->order->user;

        $this->actingAs($user);

        $data = ['reason' => 'Just feel like it.'];

        $this->json('DELETE', "/api/dashboard/booking-delete/{$booking->id}", $data)
            ->assertStatus(403)
            ->assertSee('You cannot cancel a booking that has started');
    }

    /**
     *  @test
     *  A booking cannot be cancelled if the user is not the host or renter
     */
    public function a_booking_cannot_be_cancelled_if_the_user_is_not_the_host_or_renter()
    {
        $this->createSmallDatabase();

        $booking = Booking::where('from', '>', Carbon::now())->first();

        $user = User::factory()->create();

        $this->actingAs($user);

        $data = ['reason' => 'Just feel like it.'];

        $this->json('DELETE', "/api/dashboard/booking-delete/{$booking->id}", $data)
            ->assertStatus(403)
            ->assertSee('You cannot cancel this booking');
    }
    
    /**
     *  @test
     *  A renter can cancel a booking they made
     */
    public function a_renter_can_cancel_their_future_booking()
    {
        $this->createSmallDatabase();

        $user = User::factory()->create();

        $this->actingAs($user);

        $this->authorizeUserToDrive($user);

        $response = $this->successfulCheckout();

        $order = Order::where('payment_method', $response['payment_method_id'])
            ->first();

        $booking = $order->bookings->first();

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id
        ]);

        $data = ['reason' => 'Just feel like it.'];

        $this->json('DELETE', "/api/dashboard/booking-delete/{$booking->id}", $data)
            ->assertStatus(200)
            ->assertSee('Booking canceled');
    }

    /**
     *  @test
     *  A host can cancel a booking of their vehicle
     */
    public function a_host_can_cancel_a_booking_of_their_vehicle()
    {
        $this->createSmallDatabase();

        $renter = User::factory()->create();

        $this->actingAs($renter);

        $this->authorizeUserToDrive($renter);

        $response = $this->successfulCheckout();

        $order = Order::where('payment_method', $response['payment_method_id'])
            ->first();

        $booking = $order->bookings->first();

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id
        ]);

        $host = $booking->vehicle->user;

        $this->actingAs($host);

        $data = ['reason' => 'Just feel like it.'];

        $this->json('DELETE', "/api/dashboard/booking-delete/{$booking->id}", $data)
            ->assertStatus(200)
            ->assertSee('Booking canceled');
    }

    /**
     *  @test
     *  The booking is deleted in the database and a cancellation is created
     */
    public function the_booking_is_deleted_in_the_database_and_a_cancellation_is_created()
    {
        $this->createSmallDatabase();

        $user = User::factory()->create();

        $this->actingAs($user);

        $this->authorizeUserToDrive($user);

        $response = $this->successfulCheckout();

        $order = Order::where('payment_method', $response['payment_method_id'])
            ->first();

        $booking = $order->bookings->first();

        $data = ['reason' => 'Just feel like it.'];

        $this->json('DELETE', "/api/dashboard/booking-delete/{$booking->id}", $data);

        // The booking has been deleted
        $oldBooking = Booking::find($booking->id);
        $this->assertNull($oldBooking);

        // A cancellation with the order id and reason has been created 
        $cancellation = Cancellation::where('order_id', $order->id)->first();
        $this->assertNotNull($cancellation);
        $this->assertEquals('Just feel like it.', $cancellation->reason);
    }
}
