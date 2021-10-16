<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Vehicle;
use Carbon\Carbon;
use Database\Seeders\Testing\TestingVehicleMakeModelSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Trait\UserTrait;

class VehicleTest extends TestCase
{
    use RefreshDatabase, UserTrait;

    /**
     *  @test
     *  The api route returns error 422 when the from and to query strings
     *  are absent.
     */
    public function api_route_returns_422_when_from_and_to_dates_are_absent()
    {
        $this->json('GET', '/api/vehicles-index')
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

        $this->json('GET', '/api/vehicles-index', ['from' => $from, 'to' => $to])
            ->assertStatus(422);
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

        $this->json('GET', '/api/vehicles-index', ['from' => $from, 'to' => $to])
            ->assertStatus(422);
    }

    /**
     *  @test
     *  A paginated index of vehicles is returned from the api endpoint
     */
    public function a_paginated_index_of_vehicles_are_displayed_as_json()
    {
        $this->createSmallDatabase();

        $dates = $this->setVehicleIndexDateRange();

        $response = $this->json('GET', '/api/vehicles-index', [
            'from' => $dates['from'], 
            'to' => $dates['to']
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'year',
                        'price_day',
                        'model',
                        'vehicle_make',
                        'bookings_count',
                        'image'
                    ]
                ],
                'links' => [
                    'first', 'last', 'prev', 'next'
                ],
                'meta' => [
                    'current_page',
                    'from',
                    'last_page',
                    'links' => [
                        '*' => [
                            'url', 'label', 'active'
                        ]
                    ],
                    'path',
                    'per_page',
                    'to',
                    'total'
                ]
            ]);
    }

    /**
     *  @test
     *  Inactive vehicles are not displayed in the index
     */
    public function inactive_vehicles_are_not_displayed_in_the_index()
    {
        TestingVehicleMakeModelSeeder::run();

        $dates = $this->setVehicleIndexDateRange();

        User::factory()->create([
            'host' => true
        ]);

        Vehicle::factory()->create([
            'vehicle_model_id' => 1,
            'price_day' => 999,
            'active' => true
        ]);

        Vehicle::factory()->create([
            'vehicle_model_id' => 1,
            'price_day' => 888,
            'active' => false
        ]);

        $this->json('GET', '/api/vehicles-index', [
            'from' => $dates['from'], 
            'to' => $dates['to']
        ])
            ->assertJsonFragment([
                'price_day' => '999'
            ])
            ->assertJsonMissing([
                'price_day' => '888'
            ]);
    }

    /**
     *  @test
     *  The vehicle show api route returns 200 repponse with correct JSON structure
     */
    public function the_vehicle_show_route_returns_the_correct_response_and_json_structure()
    {
        $this->createSmallDatabase();

        $vehicle = Vehicle::inRandomOrder()->first();

        $response = $this->json('GET', '/api/vehicle-show/' . $vehicle->id);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'vehicle_model',
                    'vehicle_make',
                    'year',
                    'price',
                    'description',
                    'doors',
                    'seats',
                    'vehicle_images',
                    'host_name',
                    'top_host',
                    'member_since',
                    'host_avatar',
                    'host_total_trips',
                    'host_rating',
                    'vehicle_rating',
                    'vehicle_review_count',
                    'vehicle_trip_count'
                ]
            ])->assertJsonFragment([
                'id' => $vehicle->id
            ]);
    }

    /**
     *  Set a valid daterange for indexing vehicles
     * 
     *  @return array
     */
    private function setVehicleIndexDateRange() : array
    {
        return [
            'from' => Carbon::now()->addDays(3)->format('m/d/Y'),
            'to' => Carbon::now()->addDays(10)->format('m/d/Y')
        ];
    }
}
