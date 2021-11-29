<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Trait\UserTrait;

class UserReviewTest extends TestCase
{
    use RefreshDatabase, UserTrait;

    /**
     *  @test
     *  Unauthenticated users cannot access the host-users-reviews-complete api endpoint
     */
    public function unauthenticated_users_cannot_access_the_host_users_reviews_complete_endpoint()
    {
        $this->json('GET', '/api/dashboard/host-users-reviews-complete')
            ->assertStatus(401);
    }

    /**
     *  @test
     *  A paginated index of reviews that the user has left of hosts is returned
     */
    public function a_paginated_index_of_reviews_the_user_has_left_of_hosts_is_returned()
    {
        $this->createSmallDatabase();

        $user = User::where('host', 0)->first();

        $this->actingAs($user);

        $response = $this->json('GET', '/api/dashboard/host-users-reviews-complete');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'booking' => [
                            'id', 'from', 'to', 'order_id', 'vehicle_id'
                        ],
                        'host' => [
                            'id', 'image', 'name'
                        ],
                        'hostReview' => ['id', 'content', 'rating'],
                        'vehicle' => [
                            'featured_image', 'make', 'model', 'year'
                        ]
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
}
