<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use Tests\Trait\UserTrait;

class UserVehicleTest extends TestCase
{
    use RefreshDatabase, UserTrait;

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
}
