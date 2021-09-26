<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Trait\CheckoutTrait;
use Tests\Trait\UserTrait;

class CheckoutTest extends TestCase
{
    use RefreshDatabase, UserTrait, CheckoutTrait;

    /**
     *  @test
     *  Unauthorized cannot access the checkout api endpoint.
     */
    public function unauthorized_users_cannot_access_the_checkout_api_endpoint()
    {
        $this->json('POST', '/api/checkout')->assertStatus(401);
    }

    /**
     *  @test
     *  A 403 response is returned if the user does not have a drivers license stored
     */
    public function response_403_is_returned_if_a_user_does_not_have_a_drivers_license_stored()
    {
        $this->createSmallDatabase();

        $user = User::factory()->create();

        $this->actingAs($user);

        $data = $this->validCheckoutData();

        $response = $this->json('POST', '/api/checkout', $data);

        $response->assertStatus(403)
            ->assertSee('You must verify ID prior to booking');
    }
}
