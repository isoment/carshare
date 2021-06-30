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
    public function the_vehicle_make_list_api_route_returns_200_status()
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
        $vehicle1 = VehicleMake::factory()->create();
        $vehicle2 = VehicleMake::factory()->create();
        $vehicle3 = VehicleMake::factory()->create();

        $response = $this->json('GET', '/api/vehicle-make/list');

        $response->assertJsonStructure([
            'data' => [
                '*' => ['id', 'make', 'image']
            ]
        ])->assertJsonFragment([
            'id' => $vehicle1->id,
            'make' => $vehicle1->make,
            'image' => $vehicle1->image
        ])->assertJsonFragment([
            'id' => $vehicle2->id,
            'make' => $vehicle2->make,
            'image' => $vehicle2->image
        ])->assertJsonFragment([
            'id' => $vehicle3->id,
            'make' => $vehicle3->make,
            'image' => $vehicle3->image
        ]);
    }
}
