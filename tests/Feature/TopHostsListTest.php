<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\HostReview;
use App\Models\RenterReview;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class TopHostsListTest extends TestCase
{
    use RefreshDatabase;

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
                    'id', 'host_name', 'created_at', 'rating', 'content', 
                    'host_review_count', 'renter_name'
                ]
            ]
        ]);
    }
}
