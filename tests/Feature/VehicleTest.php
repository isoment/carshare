<?php

namespace Tests\Feature;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class VehicleTest extends TestCase
{
    use RefreshDatabase;

    /**
     *  @test
     *  The api route returns error 422 when the from and to query strings
     *  are absent.
     */
    public function api_route_returns_422_when_from_and_to_are_absent()
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

        $from = Carbon::now()->addDays(3)->format('m/d/Y');
        $to = Carbon::now()->addDays(10)->format('m/d/Y');

        $response = $this->json('GET', '/api/vehicles-index', ['from' => $from, 'to' => $to]);

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
}
