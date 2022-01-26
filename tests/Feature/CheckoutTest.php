<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\DriversLicense;
use App\Models\Order;
use App\Models\User;
use App\Models\Vehicle;
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

        $this->authorizeUserToDrive($user);

        $this->actingAs($user);

        // Set the vehicle vehicle host id to the authenticated user
        $data = $this->validCheckoutData($user->id);

        $response = $this->json('POST', '/api/checkout', $data);

        $response->assertStatus(403)->assertSee('You cannot book your own vehicle');
    }

    /**
     *  @test
     *  A 422 error is returned if the vehicle is inactive
     */
    public function response_422_is_returned_if_the_vehicle_is_inactive()
    {
        $this->createSmallDatabase();

        $user = User::factory()->create();

        $this->authorizeUserToDrive($user);

        $vehicle = Vehicle::find(1);

        $vehicle->update(['active' => 0]);

        $this->actingAs($user);

        $data = $this->validCheckoutData();

        $response = $this->json('POST', '/api/checkout', $data);

        $response->assertStatus(422)
            ->assertSee("The vehicle is not active.");
    }

    /**
     *  @test
     *  A 422 response is returned if the cart is empty.
     */
    public function response_422_is_returned_if_the_cart_is_empty()
    {
        $this->createSmallDatabase();

        $user = User::factory()->create();

        $this->authorizeUserToDrive($user);

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

        $this->authorizeUserToDrive($user);

        $this->actingAs($user);

        $data = $this->validCheckoutData();

        $data['payment_method_id'] = '';

        $response = $this->json('POST', '/api/checkout', $data);

        $response->assertStatus(422)
            ->assertSee("The payment method id field is required.");
    }

    /**
     *  @test
     *  A 422 response is returned if the vehicle is unavailable
     */
    public function response_422_is_returned_if_the_vehicle_is_unavailable()
    {
        $this->createSmallDatabase();

        $user = User::factory()->create();

        $this->authorizeUserToDrive($user);

        $this->actingAs($user);

        $data = $this->validCheckoutData();

        $vehicle = Vehicle::find(1);

        $booking = $vehicle->bookings->first();

        $from = Carbon::parse($booking->from)->format('n/j/Y');
        $to = Carbon::parse($booking->to)->format('n/j/Y');

        $data['cart'][0]['dates']['start'] = $from;
        $data['cart'][0]['dates']['end'] = $to;

        $response = $this->json('POST', '/api/checkout', $data);

        $response->assertStatus(422)
            ->assertSee("is not available between");
    }

    /**
     *  @test
     *  A 422 response is returned if the user already has a booking for the dates
     */
    public function response_422_is_returned_if_the_user_has_a_booking()
    {
        $this->createSmallDatabase();

        $user = User::first();

        $this->actingAs($user);

        $order = $user->orders->first();

        $booking = Booking::where('order_id', $order->id)->first();

        $from = Carbon::now()->addYears(1);
        $to = Carbon::parse($from)->addWeek();

        $booking->update([
            'from' => $from,
            'to' => $to
        ]);

        $data = $this->validCheckoutData();

        $response = $this->json('POST', '/api/checkout', $data);

        $response->assertStatus(422)
            ->assertSee("You already have a booking between");
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

        $this->authorizeUserToDrive($user);

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

        $this->authorizeUserToDrive($user);

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

        $this->actingAs($user);

        $this->authorizeUserToDrive($user);

        $response = $this->successfulCheckout($user);

        $response['response']->assertStatus(201)->assertSee('Success');
    }

    /**
     *  @test
     *  The database has the new order and booking record when payment is successful.
     */
    public function the_database_has_the_new_order_and_booking_record_when_payment_is_successful()
    {
        $this->createSmallDatabase();

        $user = User::factory()->create();

        $this->actingAs($user);

        $this->authorizeUserToDrive($user);

        $data = $this->validCheckoutData();

        $response = $this->successfulCheckout($user, $data);

        $this->assertDatabaseHas('orders', [
            'payment_method' => $response['payment_method_id']
        ]);

        $order = Order::where('payment_method', $response['payment_method_id'])
            ->first();

        $this->assertDatabaseHas('bookings', [
            'order_id' => $order->id
        ]);
    }

    /**
     *  @test
     *  The database has a host and renter review for the new booking
     */
    public function the_database_has_a_host_and_renter_review_for_the_new_booking()
    {
        $this->createSmallDatabase();

        $user = User::factory()->create();

        $this->actingAs($user);

        $this->authorizeUserToDrive($user);

        $response = $this->successfulCheckout($user);

        $order = Order::where('payment_method', $response['payment_method_id'])
            ->first();

        $booking = Booking::where('order_id', $order->id)->first();

        $this->assertDatabaseHas('renter_reviews', [
            'id' => $booking->renter_review_key
        ]);

        $this->assertDatabaseHas('host_reviews', [
            'id' => $booking->host_review_key
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

        $this->actingAs($user);

        $this->authorizeUserToDrive($user);

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

        $this->actingAs($user);

        $this->authorizeUserToDrive($user);

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
