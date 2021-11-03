<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\Testing\TestingVehicleMakeModelSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;
use Tests\Trait\UserTrait;
use Tests\Trait\UserVehicleTrait;

class UserVehicleTest extends TestCase
{
    use RefreshDatabase, UserTrait, UserVehicleTrait;

    /**
     *  @test
     *  Unauthenticated users cannot access the users vehicle index api endpoint
     */
    public function unauthenticated_users_cannot_access_the_users_vehicle_index_api_route()
    {
        $this->json('GET', '/api/dashboard/index-users-vehicles')
            ->assertStatus(401);
    }

    /**
     *  @test
     *  A paginated index of the users vehicles is displayed
     */
    public function a_paginated_index_of_users_vehicle_is_displayed_in_the_user_vehicle_index()
    {
        $this->createSmallDatabase();

        $user = User::where('host', 1)->first();

        $this->actingAs($user);

        $response = $this->json('GET', '/api/dashboard/index-users-vehicles');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'active',
                        'created_at',
                        'featured_image',
                        'make',
                        'model',
                        'year',
                        'price_day'
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
     *  Setting active to true in the request shows active vehicles
     */
    public function settting_active_to_true_shows_active_vehicles_in_the_user_vehicle_index_json_response()
    {
        $this->createSmallDatabase();

        $user = User::where('host', 1)->first();

        $this->actingAs($user);

        $vehicle = $user->vehicles->first();

        $response = $this->json('GET', '/api/dashboard/index-users-vehicles', [
            'active' => 'true'
        ]);

        $response->assertJsonFragment([
            'model' => $vehicle->vehicleModel->model,
            'year' => $vehicle->year,
            'price_day' => $vehicle->price_day
        ]);
    }

    /**
     *  @test
     *  Inactive vehicles are not seen in the user vehicle index
     */
    public function inactive_vehicles_are_not_seen_in_the_user_vehicle_index()
    {
        $this->createSmallDatabase();

        $user = User::where('host', 1)->first();

        $this->actingAs($user);

        $user->vehicles->each(function($vehicle) {
            $vehicle->update([
                'active' => 0
            ]);
        });

        $vehicle = $user->vehicles->first();

        $response = $this->json('GET', '/api/dashboard/index-users-vehicles', [
            'active' => 'true'
        ]);

        $response->assertJsonMissing([
            'model' => $vehicle->vehicleModel->model,
            'year' => $vehicle->year,
            'price_day' => $vehicle->price_day
        ]);
    }


    /**
     *  @test
     *  A user can only view their own vehicles in the index
     */
    public function a_user_can_only_view_their_own_vehicles_in_the_user_vehicle_index()
    {
        $this->createSmallDatabase();

        $users = User::where('host', 1)->get();

        $userOne = $users[0];
        $userTwo = $users[1];
        $userThree = $users[2];

        $this->actingAs($userOne);

        $userOneVehicle = $userOne->vehicles->first();
        $userTwoVehicle = $userTwo->vehicles->first();
        $userThreeVehicle = $userThree->vehicles->first();

        $response = $this->json('GET', '/api/dashboard/index-users-vehicles', [
            'active' => 'true'
        ]);

        $response->assertJsonFragment([
            'id' => $userOneVehicle->id,
        ])->assertJsonMissing([
            'id' => $userTwoVehicle->id
        ])->assertJsonMissing([
            'id' => $userThreeVehicle->id
        ]);
    }

    /**
     *  @test
     *  Unauthenticated users cannot access the new vehicle api endpoint
     */
    public function unauthenticated_users_cannot_access_the_new_vehicle_api_route()
    {
        $this->json('POST', '/api/dashboard/create-users-vehicles')
            ->assertStatus(401);
    }

    /**
     *  @test
     *  A user must be a host to create a vehicle
     */
    public function a_user_must_be_a_host_to_create_a_vehicle()
    {
        TestingVehicleMakeModelSeeder::run();

        $user = User::factory()->create();

        $this->actingAs($user);

        $data = $this->validNewVehicleData();

        $this->json('POST', '/api/dashboard/create-users-vehicles', $data)
            ->assertStatus(403)
            ->assertSee('You must be a host to create a vehicle');
    }

    /**
     *  @test
     *  An empty request results in 422 validation error
     */
    public function an_empty_request_results_in_422_to_the_new_vehicle_api_route()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $this->json('POST', '/api/dashboard/create-users-vehicles', [])
            ->assertStatus(422);
    }

    /**
     *  @test
     *  Image and featured image are required to create a vehicle
     */
    public function image_and_featured_image_are_required_to_create_vehicle()
    {
        TestingVehicleMakeModelSeeder::run();

        $user = User::factory()->create([
            'host' => true
        ]);

        $this->actingAs($user);

        $data = $this->validNewVehicleData([
            'images' => [],
            'featured_id' => ''
        ]);

        $this->json('POST', '/api/dashboard/create-users-vehicles', $data)
            ->assertStatus(422)
            ->assertSee('The images field is required.')
            ->assertSee('Please select a featured image');
    }

    /**
     *  @test
     *  File types other than images are rejected when creating vehilces
     */
    public function file_types_other_than_images_are_rejected_when_creating_vehicles()
    {
        TestingVehicleMakeModelSeeder::run();

        $user = User::factory()->create([
            'host' => true
        ]);

        $this->actingAs($user);

        // Create a fake pdf
        $file = UploadedFile::fake()->create('test-file.pdf', 8000, 'application/pdf');

        $data = $this->validNewVehicleData([
            'images' => [$file],
        ]);

        $this->json('POST', '/api/dashboard/create-users-vehicles', $data)
            ->assertStatus(422)
            ->assertSee('must be an image');
    }

    /**
     *  @test
     *  When valid data is submitted a new vehicle is created
     */
    public function when_valid_data_is_submitted_a_new_vehicle_is_created()
    {
        TestingVehicleMakeModelSeeder::run();

        $user = User::factory()->create([
            'host' => true
        ]);

        $this->actingAs($user);

        $data = $this->validNewVehicleData();

        $this->assertDatabaseMissing('vehicles', [
            'plate_num' => $data['plate'],
            'price_day' => $data['price']
        ]);

        $this->json('POST', '/api/dashboard/create-users-vehicles', $data)
            ->assertStatus(201);

        $this->assertDatabaseHas('vehicles', [
            'plate_num' => $data['plate'],
            'price_day' => $data['price']
        ]);
    }
}
