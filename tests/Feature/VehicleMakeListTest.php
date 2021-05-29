<?php

namespace Tests\Feature;

use App\Http\Resources\VehicleMakeListResource;
use App\Models\Vehicle;
use App\Models\VehicleMake;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class VehicleMakeListTest extends TestCase
{
    use RefreshDatabase;

    /**
     *  @test
     *  The api route has a 200 status
     */
    public function the_api_route_has_200_status()
    {
        $this->json('GET', '/api/vehicle-make/list')
            ->assertStatus(200);
    }

    /**
     *  @test
     *  The vehicle make results are displayed as json
     */
    public function the_vehicle_make_results_are_displayed_as_json()
    {
        $vehicle = VehicleMake::factory()->create();

        $response = $this->json('GET', '/api/vehicle-make/list');

        $response->assertJsonFragment([
                'id' => $vehicle->id,
                'make' => $vehicle->make,
                'image' => $vehicle->image
        ]);
    }
}
