<?php

namespace Tests\Trait;

trait UserBookingTrait
{
    public function bookingIndexStructure() : array
    {
        return [
            'data' => [
                '*' => [
                    'booking' => [
                        'id', 'from', 'to', 'price_day', 'price_total'
                    ],
                    'vehicle' => [
                        'id', 'image', 'make', 'model', 'year', 'active'
                    ]
                ]
            ],
            'links' => [
                'first', 'last', 'prev', 'next'
            ],
            'meta' => [
                'current_page',
                'from',
                'last_page',
                'links' => [
                    '*' => [
                        'url', 'label', 'active'
                    ]
                ],
                'path',
                'per_page',
                'to',
                'total'
            ]
        ];
    }

    public function validIndexQueryParams(array $params = []) : array
    {
        return [
            'type' => $params['type'] ?? 'asRenter',
            'sort' => $params['sort'] ?? 'dateAsc',
            'from' => $params['from'] ?? '1000-01-01',
            'to' => $params['to'] ?? '9999-12-31',
        ];
    }
}