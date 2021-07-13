<?php

namespace Tests\Feature;

use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

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
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'rating',
                        'content',
                        'created_at',
                        'updated_at',
                        'renter_name',
                        'renter_avatar'
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
