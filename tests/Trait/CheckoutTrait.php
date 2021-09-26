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
            'payment_method_id' => "pm_dh4329usdja83adkDH3r",
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
}