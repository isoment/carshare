<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\User;
use App\Models\Vehicle;
use Carbon\Carbon;
use Database\Seeders\Testing\TestingVehicleMakeModelSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;
use Tests\Trait\UserTrait;

class AvailabilityTest extends TestCase
{
    use RefreshDatabase, UserTrait;

    /**
     *  @test
     *  The guest vehicle availability api route returns error 422 when the from and to query strings
     *  are absent.
     */
    public function guest_vehicle_availability_api_route_returns_422_when_from_and_to_dates_are_absent()
    {
        $this->createSmallDatabase();

        $this->json('GET', '/api/vehicle-availability-guest/' . Vehicle::first()->id)
            ->assertStatus(422);
    }

    /**
     *  @test
     *  The json response from guest vehicle availability route has the correct structure
     */
    public function the_json_response_from_guest_availability_route_has_correct_structure()
    {
        $this->createSmallDatabase();

        $from = Carbon::now()->format('n/j/Y');

        $to = Carbon::parse($from)->addYear()->format('n/j/Y');

        $response = $this->json(
            'GET', 
            '/api/vehicle-availability-guest/' . Vehicle::first()->id,
            ['from' => $from, 'to' => $to]
        );

        $response->assertJsonStructure([
            'message',
            'unavailableDates' => [
                '*' => [
                    'start', 'end'
                ]
            ]
        ]);
    }

    /**
     *  @test
     *  The auth vehicle availability api route returns error 422 when the from and to query strings
     *  are absent.
     */
    public function auth_vehicle_availability_api_route_returns_422_when_from_and_to_dates_are_absent()
    {
        $this->createSmallDatabase();

        $user = User::factory()->create();

        $this->actingAs($user);

        $this->json('GET', '/api/vehicle-availability-auth/' . Vehicle::first()->id)
            ->assertStatus(422);
    }

    /**
     *  @test
     *  The auth vehicle availability api route is only accessible if a user is authenticated
     */
    public function auth_vehicle_availability_api_route_is_only_available_when_user_authenticated()
    {
        $this->createSmallDatabase();

        $this->json('GET', '/api/vehicle-availability-auth/' . Vehicle::first()->id)
            ->assertStatus(401);
    }

        /**
     *  @test
     *  The json response from guest vehicle availability route has the correct structure
     */
    public function the_json_response_from_auth_availability_route_has_correct_structure()
    {
        $this->createSmallDatabase();

        $user = User::factory()->create();

        $this->actingAs($user);

        $from = Carbon::now()->format('n/j/Y');

        $to = Carbon::parse($from)->addYear()->format('n/j/Y');

        $response = $this->json(
            'GET', 
            '/api/vehicle-availability-auth/' . Vehicle::first()->id,
            ['from' => $from, 'to' => $to]
        );

        $response->assertJsonStructure([
            'message',
            'unavailableDates' => [
                '*' => [
                    'start', 'end'
                ]
            ]
        ]);
    }

    /**
     *  @test
     *  Validation fails if the from date is in the past
     */
    public function vehicle_availability_validation_fails_if_the_from_date_is_in_the_past()
    {
        $this->createSmallDatabase();

        $from = Carbon::now()->subWeek()->format('n/j/Y');
        $to = Carbon::now()->addWeeks(1)->format('n/j/Y');

        $this->json(
            'GET', 
            '/api/vehicle-availability-guest/' . Vehicle::first()->id, 
            ['from' => $from, 'to' => $to]
        )->assertStatus(422);
    }

    /**
     *  @test
     *  Validation fails if the to date is less than the from date
     */
    public function vehicle_availability_validation_fails_if_the_to_date_is_less_than_from()
    {
        $this->createSmallDatabase();

        $from = Carbon::now()->addDays(3)->format('n/j/Y');
        $to = Carbon::now()->subMonth()->format('n/j/Y');

        $this->json(
            'GET', 
            '/api/vehicle-availability-guest/' . Vehicle::first()->id, 
            ['from' => $from, 'to' => $to]
        )->assertStatus(422);
    }

    /**
     *  @test
     *  A 404 response is returned if the vehicle is unavailable
     */
    public function vehicle_availability_response_404_is_returned_if_the_vehicle_is_unavailable()
    {
        $this->createSmallDatabase();

        $vehicle = Vehicle::inRandomOrder()->first();

        $booking = $vehicle->bookings->first();

        $from = Carbon::now()->addWeeks(40);
        $to = Carbon::now()->addWeeks(42);

        // Change the booking dates for the vehicle to the ones specified above 
        // and check against them below
        Booking::where('id', $booking->id)->update([
            'from' => $from,
            'to' => $to
        ]);

        $response = $this->json(
            'GET',
            '/api/vehicle-availability-guest/' . $vehicle->id,
            ['from' => $from->format('n/j/Y'), 'to' => $to->format('n/j/Y')]
        );

        $response->assertStatus(404)
            ->assertSee('Vehicle unavailable on these dates');
    }

    /**
     *  @test
     *  A 200 status code is returned if a vehicle is available
     */
    public function vehicle_availability_repsonse_200_is_returned_if_the_vehicle_is_available()
    {
        $this->createSmallDatabase();

        $vehicle = Vehicle::inRandomOrder()->first();

        // Delete all the bookings for the vehicle
        $vehicle->bookings()->delete();

        $from = Carbon::now()->format('n/j/Y');

        $to = Carbon::parse($from)->addYear()->format('n/j/Y');

        $response = $this->json(
            'GET',
            '/api/vehicle-availability-guest/' . $vehicle->id,
            ['from' => $from, 'to' => $to]
        );

        $response->assertStatus(200)->assertSee('Vehicle available');
    }

    /**
     *  @test
     *  A 404 response is returned if the vehicle is available but the authenticated user already has a booking.
     */
    public function vehicle_availability_response_404_is_returned_if_vehicle_is_available_but_authenticated_user_already_has_a_booking()
    {
        $this->createSmallDatabase();

        $user = User::where('host', 0)->first();

        $this->actingAs($user);

        $from = Carbon::now()->addWeeks(50);
        $to = Carbon::parse($from)->addWeeks(2);

        // Get a users booking and set it far into the future
        $usersBooking = $user->getBookings()->first();

        Booking::where('id', $usersBooking->id)->update([
            'from' => $from,
            'to' => $to
        ]);

        $vehicle = Vehicle::where('id', '!=', $usersBooking->vehicle_id)->first();

        $response = $this->json(
            'GET',
            '/api/vehicle-availability-auth/' . $vehicle->id,
            ['from' => $from->format('n/j/Y'), 'to' => $to->format('n/j/Y')]
        );

        $response->assertStatus(404)
            ->assertSee('You already have a booking on these dates');
    }

    /**
     *  @test
     *  A 404 response is returned if a user owns the vehicle
     */
    public function vehicle_availability_response_404_is_returned_if_vehicle_is_owned_by_the_user()
    {
        TestingVehicleMakeModelSeeder::run();

        $user = User::factory()->create();

        $this->actingAs($user);

        $vehicle = Vehicle::factory()->create([
            'user_id' => $user->id
        ]);

        $from = Carbon::now()->format('n/j/Y');
        $to = Carbon::parse($from)->addYear()->format('n/j/Y');

        $response = $this->json(
            'GET',
            '/api/vehicle-availability-auth/' . $vehicle->id,
            ['from' => $from, 'to' => $to]
        );

        $response->assertStatus(404)
            ->assertSee('You cannot book your own vehicle');
    }

    /**
     *  @test
     *  The api route returns error 422 when the from and to query strings
     *  are absent.
     */
    public function vehicle_price_api_route_returns_422_when_from_and_to_dates_are_absent()
    {
        $this->createSmallDatabase();

        $this->json('GET', '/api/vehicle-price/' . Vehicle::first()->id)
            ->assertStatus(422);
    }

    /**
     *  @test
     *  Validation fails if the from date is in the past
     */
    public function vehicle_price_validation_fails_if_the_from_date_is_in_the_past()
    {
        $this->createSmallDatabase();

        $from = Carbon::now()->subWeek()->format('n/j/Y');
        $to = Carbon::now()->addWeeks(1)->format('n/j/Y');

        $this->json(
            'GET', 
            '/api/vehicle-price/' . Vehicle::first()->id, 
            ['from' => $from, 'to' => $to]
        )->assertStatus(422);
    }

    /**
     *  @test
     *  Validation fails if the to date is less than the from date
     */
    public function vehicle_price_validation_fails_if_the_to_date_is_less_than_from()
    {
        $this->createSmallDatabase();

        $from = Carbon::now()->addDays(3)->format('n/j/Y');
        $to = Carbon::now()->subMonth()->format('n/j/Y');

        $this->json(
            'GET', 
            '/api/vehicle-price/' . Vehicle::first()->id, 
            ['from' => $from, 'to' => $to]
        )->assertStatus(422);
    }

    /**
     *  @test
     *  Vehicle pricing is calculated correctly and displays as json
     */
    public function vehicle_pricing_is_calculated_correctly_and_displayed_as_json()
    {
        $this->createSmallDatabase();

        $vehicle = Vehicle::inRandomOrder()->first();

        $randomDays = random_int(2, 7);

        $from = Carbon::now()->format('n/j/Y');
        $to = Carbon::parse($from)->addDays($randomDays - 1)->format('n/j/Y');

        $this->json(
            'GET', 
            '/api/vehicle-price/' . $vehicle->id, 
            ['from' => $from, 'to' => $to]
        )->assertStatus(200)
            ->assertJson([
                'data' => [
                    'days' => $randomDays,
                    'price_day' => $vehicle->price_day,
                    'total' => ($randomDays * $vehicle->price_day)
                ]
            ]);
    }

    /**
     *  @test
     *  The users booking dates api route is only allowed for authenticated users
     */
    public function the_users_booking_dates_api_route_is_only_available_for_authenticated_users()
    {
        $this->json('GET', '/api/users-booking-dates')
            ->assertStatus(401);
    }

    /**
     *  @test
     *  The response from users booking dates route contains the correct json response
     */
    public function the_response_from_users_booking_dates_route_contains_the_correct_json_structure()
    {
        $this->createSmallDatabase();

        $user = User::has('orders')->first();

        $this->actingAs($user);

        $this->json('GET', '/api/users-booking-dates')
            ->assertJsonStructure([
                'unavailableDates' => []
            ]);
    }

    /**
     *  @test
     *  The response from users booking dates route contains the users future booking date
     */
    public function the_response_from_users_booking_dates_route_contains_the_users_booking_date()
    {
        $this->createSmallDatabase();

        $user = User::has('orders.bookings')->first();

        $this->actingAs($user);

        $booking = $user->getBookings()->first();
        
        $from = Carbon::now()->addDays(120);
        $to = Carbon::now()->addDays(122);

        Booking::where('id', $booking->id)->update([
            'from' => $from,
            'to' => $to
        ]);

        $response = $this->json('GET', '/api/users-booking-dates');

        $response->assertJsonFragment([
            $from->format('m/d/Y')
        ])->assertJsonFragment([
            $to->format('m/d/Y')
        ]);
    }
}
