<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
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

    /**
     *  @test
     *  The top hosts list is cached in redis for a guest user
     */
    public function the_top_hosts_list_is_cached_in_redis_for_a_guest_user()
    {
        $this->createSmallDatabase();

        $this->clearCache(['user:test-guest:top-host-list']);

        $this->assertFalse(Cache::store('redis')->has('user:test-guest:top-host-list'));

        $this->json('GET', '/api/top-hosts/list');

        $this->assertTrue(Cache::store('redis')->has('user:test-guest:top-host-list'));
    }

    /**
     *  @test
     *  The top hosts list is cached in redis for an authenticated user
     */
    public function the_top_hosts_list_is_cached_in_redis_for_an_authenticated_user()
    {
        $this->createSmallDatabase();

        $user = User::inRandomOrder()->first();

        $this->actingAs($user);

        $this->clearCache(['user:test-auth:top-host-list', 'user:test-guest:top-host-list']);

        $this->assertFalse(Cache::store('redis')->has('user:test-auth:top-host-list'));

        $this->json('GET', '/api/top-hosts/list');

        $this->assertTrue(Cache::store('redis')->has('user:test-auth:top-host-list'));

        $this->assertFalse(Cache::store('redis')->has('user:test-guest:top-host-list'));

        $this->clearCache(['user:test-auth:top-host-list', 'user:test-guest:top-host-list']);
    }
}
