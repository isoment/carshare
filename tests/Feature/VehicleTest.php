<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleMake;
use App\Models\VehicleModel;
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
        $response = $this->json('GET', '/api/vehicles-index', ['make' => 'all']);
            
        $response->assertStatus(422)->assertJsonFragment([
            'from' => [
                'Field required'
            ],
            'to' => [
                'Field required'
            ]
        ]);
    }

    /**
     *  @test
     *  The api route returns error 422 when the make query string is absent
     */
    public function api_route_returns_422_error_when_the_make_query_string_is_absent()
    {
        $from = Carbon::now()->addWeek()->format('m/d/Y');
        $to = Carbon::now()->addWeeks(2)->format('m/d/Y');

        $response = $this->json(
            'GET', '/api/vehicles-index', ['from' => $from, 'to' => $to]
        );
            
        $response->assertStatus(422)->assertJsonFragment([
            'make' => [
                'Field required'
            ]
        ]);
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
            'to' => $dates['to'],
            'make' => 'all',
            'orderBy' => 'popularity'
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

        $this->assertEmpty(Vehicle::all());

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
            'to' => $dates['to'],
            'make' => 'all',
            'orderBy' => 'popularity'
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
     *  Vehicles can be filtered by price in the vehicles index
     */
    public function vehicles_can_be_filtered_by_price_in_the_vehicles_index()
    {
        TestingVehicleMakeModelSeeder::run();

        $dates = $this->setVehicleIndexDateRange();

        User::factory()->create([
            'host' => true
        ]);

        $this->assertEmpty(Vehicle::all());

        Vehicle::factory()->create([
            'vehicle_model_id' => 1,
            'price_day' => 250,
            'active' => true
        ]);

        Vehicle::factory()->create([
            'vehicle_model_id' => 1,
            'price_day' => 75,
            'active' => true
        ]);

        $response = $this->json('GET', '/api/vehicles-index', [
            'from' => $dates['from'], 
            'to' => $dates['to'],
            'make' => 'all',
            'min' => 50,
            'max' => 100,
            'orderBy' => 'popularity'
        ]);

        $response->assertJsonFragment([
            'price_day' => '75'
        ]);

        $response->assertJsonMissing([
            'price_day' => '250'
        ]);
    }

    /**
     *  @test
     *  Vehicles can be filtered by make in the vehicles index
     */
    public function vehicles_can_be_filtered_by_make_in_the_vehicles_index()
    {
        TestingVehicleMakeModelSeeder::run();

        $dates = $this->setVehicleIndexDateRange();

        User::factory()->create([
            'host' => true
        ]);

        $this->assertEmpty(Vehicle::all());

        $modelOne = VehicleModel::where('vehicle_make_id', VehicleMake::find(1)->id)->first();
        $modelTwo = VehicleModel::where('vehicle_make_id', VehicleMake::find(2)->id)->first();

        Vehicle::factory()->create([
            'vehicle_model_id' => $modelOne->id,
            'price_day' => 250,
            'active' => true
        ]);

        Vehicle::factory()->create([
            'vehicle_model_id' => $modelTwo->id,
            'price_day' => 75,
            'active' => true
        ]);

        $makeToSearch = $modelOne->vehicleMake->make;

        $response = $this->json('GET', '/api/vehicles-index', [
            'from' => $dates['from'], 
            'to' => $dates['to'],
            'make' => $makeToSearch,
            'orderBy' => 'popularity'
        ]);

        $response->assertJsonFragment([
            'price_day' => '250'
        ])->assertJsonFragment([
            'vehicle_make' => $makeToSearch
        ]);

        $response->assertJsonMissing([
            'price_day' => '75'
        ])->assertJsonMissing([
            'vehicle_make' => $modelTwo->vehicleMake->make
        ]);
    }

    /**
     *  @test
     *  Vehicles are filtered by availability 
     */
    public function vehicles_are_filtered_by_availability_in_vehicles_index()
    {
        TestingVehicleMakeModelSeeder::run();

        $dates = $this->setVehicleIndexDateRange();

        User::factory()->create([
            'host' => true
        ]);

        User::factory()->create([
            'host' => 0
        ]);

        $this->assertEmpty(Vehicle::all());

        Vehicle::factory()->create([
            'vehicle_model_id' => 1,
            'price_day' => 250,
            'active' => true
        ]);

        $bookedVehicle = Vehicle::factory()->create([
            'vehicle_model_id' => 1,
            'price_day' => 75,
            'active' => true
        ]);

        Booking::factory()->create([
            'vehicle_id' => $bookedVehicle->id,
            'from' => Carbon::parse($dates['from']),
            'to' => Carbon::parse($dates['to'])
        ]);

        $response = $this->json('GET', '/api/vehicles-index', [
            'from' => $dates['from'], 
            'to' => $dates['to'],
            'make' => 'all',
            'orderBy' => 'popularity'
        ]);

        $response->assertJsonMissing([
            'price_day' => '75'
        ])->assertJsonMissing([
            'bookings_count' => 1
        ])->assertJsonFragment([
            'price_day' => '250'
        ]);
    }

    /**
     *  @test
     *  The vehicles can be sorted by popularity
     */
    public function vehicles_can_be_sorted_by_popularity()
    {
        TestingVehicleMakeModelSeeder::run();

        $dates = $this->setVehicleIndexDateRange();

        User::factory()->create([
            'host' => true
        ]);

        User::factory()->create([
            'host' => 0
        ]);

        Vehicle::factory()->create([
            'vehicle_model_id' => 1,
            'price_day' => 250,
            'active' => true
        ]);

        $mostPopularVehicle = Vehicle::factory()->create([
            'vehicle_model_id' => 1,
            'price_day' => 75,
            'active' => true
        ]);

        Booking::factory()->create([
            'vehicle_id' => $mostPopularVehicle->id,
            'from' => Carbon::parse($dates['from']),
            'to' => Carbon::parse($dates['to'])
        ]);

        $secondBookingFrom = Carbon::parse($dates['to'])->addDays(2);
        $secondBookingTo = $secondBookingFrom->addDays(5);

        Booking::factory()->create([
            'vehicle_id' => $mostPopularVehicle->id,
            'from' => $secondBookingFrom,
            'to' => $secondBookingTo
        ]);

        $response = $this->json('GET', '/api/vehicles-index', [
            'from' => Carbon::now()->addMonths(4)->format('m/d/Y'), 
            'to' => Carbon::now()->addMonths(5)->format('m/d/Y'),
            'make' => 'all',
            'orderBy' => 'popularity'
        ]);

        $response->assertJsonFragment(['bookings_count' => '2'])
            ->assertSeeInOrder(['75', '250']);
    }

    /**
     *  @test
     *  The vehicles can be sorted by price
     */
    public function vehicles_can_be_sorted_by_price()
    {
        TestingVehicleMakeModelSeeder::run();

        $dates = $this->setVehicleIndexDateRange();

        User::factory()->create([
            'host' => true
        ]);

        $this->assertEmpty(Vehicle::all());

        Vehicle::factory()->create([
            'vehicle_model_id' => 1,
            'price_day' => 185,
            'active' => true
        ]);

        Vehicle::factory()->create([
            'vehicle_model_id' => 1,
            'price_day' => 45,
            'active' => true
        ]);

        Vehicle::factory()->create([
            'vehicle_model_id' => 1,
            'price_day' => 90,
            'active' => true
        ]);

        $response = $this->json('GET', '/api/vehicles-index', [
            'from' => $dates['from'], 
            'to' => $dates['to'],
            'make' => 'all',
            'orderBy' => 'priceLow'
        ]);

        $response->assertSeeInOrder(['45', '90', '185']);

        $response = $this->json('GET', '/api/vehicles-index', [
            'from' => $dates['from'], 
            'to' => $dates['to'],
            'make' => 'all',
            'orderBy' => 'priceHigh'
        ]);

        $response->assertSeeInOrder(['185', '90', '45']);
    }

    /**
     *  @test
     *  The vehicle show api route returns 200 response with correct JSON structure
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
                    'latitude',
                    'longitude',
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
