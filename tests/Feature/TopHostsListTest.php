<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Trait\UserTrait;

class TopHostsListTest extends TestCase
{
    use RefreshDatabase, UserTrait;

    /**
     *  @test
     *  The top hosts list results are displayed as json correctly
     */
    public function the_top_hosts_list_results_are_displayed_as_json_correctly()
    {
        $this->createSmallDatabase();

        $response = $this->json('GET', '/api/top-hosts/list');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id', 'host_name', 'host_avatar', 'created_at', 'rating', 'content', 
                        'host_review_count', 'renter_name'
                    ]
                ]
            ]);
    }
}
