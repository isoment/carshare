<?php

namespace Tests\Feature;

use App\Models\User;
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
     *  Error getting bookings 404 is returned if the booking type is invalid
     */
    // public function error_getting_bookings_404_is_returned_if_the_booking_type_is_invalid()
    // {
    //     $this->createSmallDatabase();

    //     $user = User::has('orders')->where('host', 0)->first();

    //     $this->actingAs($user);

    //     $this->json('GET', '/api/dashboard/booking-index', ['type' => 'abc123'])
    //         ->assertStatus(404)
    //         ->assertSee('Error getting bookings');
    // }
}
