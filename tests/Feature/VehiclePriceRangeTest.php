<?php

namespace Tests\Feature;

use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VehiclePriceRangeTest extends TestCase
{
    use RefreshDatabase;

    /**
     *  @test
     *  The api route has a 200 status
     */
    public function the_vehicle_price_range_api_route_returns_200_status()
    {
        $this->json('GET', '/api/vehicles/price-range')
            ->assertStatus(200);
    }

    /**
     *  @test
     *  The min and max price are returned from the Vehicle model
     *  and displayed as json
     */
    public function min_and_max_price_of_all_vehicles_returned_as_json()
    {
        $this->createSmallDatabase();

        $vehicles = Vehicle::priceRange();

        $response = $this->json('GET', '/api/vehicles/price-range');

        $response->assertJson([
            'max' => $vehicles['max'],
            'min' => $vehicles['min']
        ]);
    }
}
