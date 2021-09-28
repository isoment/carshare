<?php

namespace Tests\Trait;

use Carbon\Carbon;

trait CheckoutTrait
{
    /**
     *  Valid json data for testing the checkout.
     * 
     *  @return array
     */
    public function validCheckoutData() : array
    {
        $start = Carbon::now()->addYears(1)->format('n/j/Y');
        $end = Carbon::parse($start)->addDays(2)->format('n/j/Y');

        return [
            'payment_method_id' => "pm_999999999999999",
            'cart' => [
                [
                    'vehicle_id' => 1,
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
     * 
     *  @return \Stripe\PaymentMethod
     */
    public function createStripePaymentMethod(array $params = []) : \Stripe\PaymentMethod
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
}