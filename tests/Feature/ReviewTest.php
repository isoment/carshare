<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Trait\UserTrait;

class ReviewTest extends TestCase
{
    use RefreshDatabase, UserTrait;

    /**
     *  The format we expect to see for an index of reviews
     * 
     *  @return array
     */
    public function reviewIndexJsonStructure()
    {
        return [
            'data' => [
                '*' => [
                    'rating',
                    'content',
                    'created_at',
                    'updated_at',
                    'reviewer_name',
                    'reviewer_avatar'
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
        ];
    }

    /**
     *  @test
     *  A paginated index is returned of a vehicles reviews
     */
    public function the_vehicles_reviews_are_displayed_as_json()
    {
        $this->createSmallDatabase();

        $randomVehicle = Vehicle::all()->random();

        $response = $this->json('GET', '/api/reviews-vehicle/' . $randomVehicle->id);

        $response->assertStatus(200)
            ->assertJsonStructure(
                $this->reviewIndexJsonStructure()
            );
    }

    /**
     *  @test
     *  A paginated index is returned of a users reviews from hosts
     */
    public function the_users_reviews_from_hosts_are_displayed_as_json()
    {
        $this->createSmallDatabase();

        $randomUser = User::all()->random();

        $response = $this->json('GET', '/api/reviews-from-hosts/' . $randomUser->id);

        $response->assertStatus(200)
            ->assertJsonStructure(
                $this->reviewIndexJsonStructure()
            );
    }

    /**
     *  @test
     *  A paginated index is returned of a users reviews from renters
     */
    public function the_users_reviews_from_renters_are_displayed_as_json()
    {
        $this->createSmallDatabase();

        $randomUser = User::all()->random();

        $response = $this->json('GET', '/api/reviews-from-renters/' . $randomUser->id);

        $response->assertStatus(200)
            ->assertJsonStructure(
                $this->reviewIndexJsonStructure()
            );
    }
}
