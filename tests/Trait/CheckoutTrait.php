<?php

namespace Tests\Trait;

use App\Models\DriversLicense;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Testing\TestResponse;

trait CheckoutTrait
{
    use UserTrait;

    /**
     *  Valid json data for testing the checkout.
     * 
     *  @param int $hostId
     *  @return array
     */
    private function validCheckoutData(int $hostId = null) : array
    {
        $start = Carbon::now()->addYears(1)->format('n/j/Y');
        $end = Carbon::parse($start)->addDays(2)->format('n/j/Y');

        return [
            'payment_method_id' => "pm_999999999999999",
            'cart' => [
                [
                    'vehicle_id' => 1,
                    'host_id' => $hostId ? $hostId : 999,
                    'dates' => [
                        'start' => $start,
                        'end' => $end
                    ],
                    'price' => [
                        'days' => 3,
                        'price_day' => 100,
                        'total' => 300
                    ]
                ]
            ]
        ];
    }

    /**
     *  Create a stripe payment method, simulating what stripe.js is doing
     *  in the Payment.vue component.
     * 
     *  @param array $params
     *  @return \Stripe\PaymentMethod
     */
    private function createStripePaymentMethod(array $params = []) : \Stripe\PaymentMethod
    {
        $stripe = new \Stripe\StripeClient(
            env('STRIPE_SECRET')
        );

        return $stripe->paymentMethods->create([
            'type' => $params['type'] ?? 'card',
            'card' => [
                'number' => $params['number'] ?? '4242424242424242',
                'exp_month' => 5,
                'exp_year' => (int) Carbon::now()->addYears(5)->format('Y'),
                'cvc' => 532
            ]
        ]);
    }

    /**
     *  Simulate a successful checkout
     * 
     *  @return array with TestResponse object and payment_method_id
     */
    private function successfulCheckout() : array
    {
        $data = $this->validCheckoutData();

        $response = $this->createStripePaymentMethod();

        $data['payment_method_id'] = $response['id'];

        $response = $this->json('POST', '/api/checkout', $data);

        return [
            'response' => $response,
            'payment_method_id' => $data['payment_method_id']
        ];
    }
}