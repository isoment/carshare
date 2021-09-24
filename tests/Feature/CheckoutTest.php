<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    /**
     *  @test
     *  Unauthorized cannot access the checkout api endpoint.
     */
    public function unauthorized_users_cannot_access_the_checkout_api_endpoint()
    {
        $this->json('POST', '/api/checkout')->assertStatus(401);
    }
}
