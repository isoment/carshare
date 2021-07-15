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
    public function vehicle_availability_validation_fails_if_the_from_date_is_in_the_past()
    {
        $this->createSmallDatabase();

        $from = Carbon::now()->subWeek()->format('n/j/Y');
        $to = Carbon::now()->addWeeks(1)->format('n/j/Y');

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
    public function vehicle_availability_validation_fails_if_the_to_date_is_less_than_from()
    {
        $this->createSmallDatabase();

        $from = Carbon::now()->addDays(3)->format('n/j/Y');
        $to = Carbon::now()->subMonth()->format('n/j/Y');

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
    public function vehicle_availability_response_404_is_returned_if_the_vehicle_is_unavailable()
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
    public function vehicle_availability_repsonse_200_is_returned_if_the_vehicle_is_available()
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

        $vehicle = Vehicle::first();

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
}
