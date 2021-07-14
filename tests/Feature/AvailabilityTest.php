<?php

namespace Tests\Feature;

use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Tests\TestCase;

class AvailabilityTest extends TestCase
{
    use RefreshDatabase;

    /**
     *  @test
     *  The api route returns error 422 when the from and to query strings
     *  are absent.
     */
    public function vehicle_availability_check_api_route_returns_422_when_from_and_to_dates_are_absent()
    {
        $this->createSmallDatabase();

        $this->json('GET', '/api/vehicle-availability/' . Vehicle::first()->id)
            ->assertStatus(422);
    }

    /**
     *  @test
     *  Validation fails if the from date is in the past
     */
    public function validation_fails_if_the_from_date_is_in_the_past()
    {
        $this->createSmallDatabase();

        $from = Carbon::now()->subWeek()->format('m/d/Y');
        $to = Carbon::now()->addWeeks(1)->format('m/d/Y');

        $this->json(
            'GET', 
            '/api/vehicle-availability/' . Vehicle::first()->id, 
            ['from' => $from, 'to' => $to]
        )->assertStatus(422);
    }

    /**
     *  @test
     *  Validation fails if the to date is less than the from date
     */
    public function validation_fails_if_the_to_date_is_less_than_from()
    {
        $this->createSmallDatabase();

        $from = Carbon::now()->addDays(3)->format('m/d/Y');
        $to = Carbon::now()->subMonth()->format('m/d/Y');

        $this->json(
            'GET', 
            '/api/vehicle-availability/' . Vehicle::first()->id, 
            ['from' => $from, 'to' => $to]
        )->assertStatus(422);
    }

    /**
     *  @test
     *  A 404 response is returned if the vehicle is unavailable
     */
    public function response_404_is_returned_if_the_vehicle_is_unavailable()
    {
        $this->createSmallDatabase();

        $vehicle = Vehicle::find(1);

        $booking = $vehicle->bookings->where('from', '>=', Carbon::now()->addDay())->first();

        $from = Carbon::parse($booking->from)->subDay()->format('n/j/Y');

        $to = Carbon::parse($booking->from)->addDays(3)->format('n/j/Y');

        $response = $this->json(
            'GET',
            '/api/vehicle-availability/' . $vehicle->id,
            ['from' => $from, 'to' => $to]
        );

        $response->assertStatus(404)->assertJson([]);
    }

    /**
     *  @test
     *  A 200 status code is returned if a vehicle is available
     */
    public function repsonse_200_is_returned_if_the_vehicle_is_available()
    {
        $this->createSmallDatabase();

        $vehicle = Vehicle::find(1);

        // Delete all the bookings for the vehicle
        $vehicle->bookings()->delete();

        $from = Carbon::now()->format('n/j/Y');

        $to = Carbon::parse($from)->addYear()->format('n/j/Y');

        $response = $this->json(
            'GET',
            '/api/vehicle-availability/' . $vehicle->id,
            ['from' => $from, 'to' => $to]
        );

        $response->assertStatus(200)->assertJson([]);
    }
}
