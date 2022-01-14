<?php

namespace Tests\Feature;

use App\Models\DriversLicense;
use App\Models\Order;
use App\Models\User;
use App\Notifications\OrderConfirmation;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
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
     *  A 403 response is returned if the user does not have a drivers license stored.
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

    /**
     *  @test
     *  A 403 error is returned if the user is trying to book their own vehicle
     */
    public function response_403_is_returned_when_user_tries_booking_their_own_vehicle()
    {
        $this->createSmallDatabase();

        $user = User::factory()->create();

        DriversLicense::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user);

        // Set the vehicle vehicle host id to the authenticated user
        $data = $this->validCheckoutData($user->id);

        $response = $this->json('POST', '/api/checkout', $data);

        $response->assertStatus(403)->assertSee('You cannot book your own vehicle');
    }

    /**
     *  @test
     *  A 422 response is returned if the cart is empty.
     */
    public function response_422_is_returned_if_the_cart_is_empty()
    {
        $this->createSmallDatabase();

        $user = User::factory()->create();

        DriversLicense::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user);

        $data = $this->validCheckoutData();

        $data['cart'] = [];

        $response = $this->json('POST', '/api/checkout', $data);

        $response->assertStatus(422)
            ->assertSee("The cart field is required.");
    }

    /**
     *  @test
     *  A 422 response is returned if the payment_method_id is missing.
     */
    public function response_422_is_returned_if_the_payment_method_id_is_missing()
    {
        $this->createSmallDatabase();

        $user = User::factory()->create();

        DriversLicense::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user);

        $data = $this->validCheckoutData();

        $data['payment_method_id'] = '';

        $response = $this->json('POST', '/api/checkout', $data);

        $response->assertStatus(422)
            ->assertSee("The payment method id field is required.");
    }

    /**
     *  @test
     *  A 404 response is returned if the vehicle id is invalid since it will not
     *  be found on the Vehicle model.
     */
    public function response_404_is_returned_if_the_vehicle_id_is_invalid_and_doesnt_exist_on_the_vehicle_model()
    {
        $this->createSmallDatabase();

        $user = User::factory()->create();

        DriversLicense::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user);

        $data = $this->validCheckoutData();

        $data['cart'][0]['vehicle_id'] = 9999;

        $response = $this->json('POST', '/api/checkout', $data);

        $response->assertStatus(404);
    }

    /**
     *  @test
     *  A 422 response is returned when the start date is invalid.
     */
    public function response_422_is_returned_if_the_start_date_is_invalid()
    {
        $this->createSmallDatabase();

        $user = User::factory()->create();

        DriversLicense::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user);

        $data = $this->validCheckoutData();

        $start = Carbon::now()->subWeek()->format('n/j/Y');

        $data['cart'][0]['dates']['start'] = $start;

        $response = $this->json('POST', '/api/checkout', $data);

        $response->assertStatus(422)
            ->assertSee('Start date must be after or equal to now.');
    }

    /**
     *  @test
     *  Checkout endpoint returns a 201 response when input data and card is valid.
     */
    public function checkout_endpoint_returns_201_response_when_checkout_is_successful()
    {
        $this->createSmallDatabase();

        $user = User::factory()->create();

        DriversLicense::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user);

        $data = $this->validCheckoutData();

        $response = $this->createStripePaymentMethod();

        $data['payment_method_id'] = $response['id'];

        $response = $this->json('POST', '/api/checkout', $data);

        $response->assertStatus(201)->assertSee('Success');
    }

    /**
     *  @test
     *  The database has the new order and booking record when payment is successful.
     */
    public function the_database_has_the_new_order_and_booking_record_when_payment_is_successful()
    {
        $this->createSmallDatabase();

        $user = User::factory()->create();

        DriversLicense::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user);

        $data = $this->validCheckoutData();

        $response = $this->createStripePaymentMethod();

        $data['payment_method_id'] = $response['id'];

        $response = $this->json('POST', '/api/checkout', $data);

        $this->assertDatabaseHas('orders', [
            'payment_method' => $data['payment_method_id']
        ]);

        $order = Order::where('payment_method', $data['payment_method_id'])->first();

        $this->assertDatabaseHas('bookings', [
            'order_id' => $order->id
        ]);
    }

    /**
     *  @test
     *  An invalid stripe payment id results in an error processing payment.
     */
    public function an_invalid_stripe_payment_id_results_in_an_error_processing_payment()
    {
        $this->createSmallDatabase();

        $user = User::factory()->create();

        DriversLicense::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user);

        $data = $this->validCheckoutData();

        $response = $this->json('POST', '/api/checkout', $data);

        $response->assertStatus(500)->assertSee('Error processing payment');
    }

    /**
     *  @test
     *  An order processed successfully, a confirmation email is sent.
     */
    public function after_an_order_processes_successfully_a_confirmation_email_is_sent()
    {
        $this->createSmallDatabase();

        $user = User::factory()->create();

        DriversLicense::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user);

        $data = $this->validCheckoutData();

        $response = $this->createStripePaymentMethod();

        $data['payment_method_id'] = $response['id'];

        Notification::fake();

        $response = $this->json('POST', '/api/checkout', $data);

        Notification::assertSentTo(
            $user,
            \App\Notifications\OrderConfirmation::class
        );
    }
}
