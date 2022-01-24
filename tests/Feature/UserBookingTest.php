<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Trait\UserBookingTrait;
use Tests\Trait\UserTrait;

class UserBookingTest extends TestCase
{
    use RefreshDatabase, UserTrait, UserBookingTrait;

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
}
