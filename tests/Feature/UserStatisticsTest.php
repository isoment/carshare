<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Trait\UserTrait;
use Tests\Trait\StatisticsTrait;

class UserStatisticsTest extends TestCase
{
    use RefreshDatabase, UserTrait, StatisticsTrait;

    /**
     *  @test
     *  An unauthenticated user gets a 401 response from renter stats api endpoint
     */
    public function an_unauthenticated_user_gets_a_401_response_from_renter_stats_api_endpoint()
    {
        $this->json('GET', '/api/dashboard/renter-stats')
            ->assertStatus(401);
    }

    /**
     *  @test
     *  The response from the renter stats api endpoint has the correct structure
     */
    public function the_response_from_the_renter_stats_api_endpoint_has_the_correct_json_structure()
    {
        $this->createSmallDatabase();

        $user = User::has('orders')->where('host', 0)->first();

        $this->actingAs($user);

        $response = $this->json('GET', '/api/dashboard/renter-stats');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'basic' => [
                    'totalSpent', 'bookingCount', 'cancelCount', 'orderCount', 'orderAverage'
                ],
                'bookingCountByMonth' => [
                    'month', 'count'
                ],
                'totalsByMonth' => [
                    'month', 'total'
                ],
                'recentBookings' => [
                    '*' => [
                        'booking' => [
                            'id', 'from', 'to', 'price_day', 'price_total', 'created_at'
                        ],
                        'vehicle' => [
                            'id', 'image', 'make', 'model', 'year', 'active'
                        ]
                    ]
                ]
            ]);
    }

    /**
     *  @test
     *  The correct renters total spent is in the response
     */
    public function the_correct_renters_total_spent_is_in_the_response()
    {
        $this->createSmallDatabase();

        $user = User::has('orders')->where('host', 0)->first();

        $this->actingAs($user);

        $response = $this->json('GET', '/api/dashboard/renter-stats');

        $response->assertJsonFragment([
            'totalSpent' => $user->orders->sum('total')
        ]);
    }

    /**
     *  @test
     *  The correct renters booking count is in the response
     */
    public function the_correct_renters_booking_count_is_in_the_response()
    {
        $this->createSmallDatabase();

        $user = User::has('orders')->where('host', 0)->first();

        $this->actingAs($user);

        $response = $this->json('GET', '/api/dashboard/renter-stats');

        $response->assertJsonFragment([
            'bookingCount' => $user->getBookings()->count()
        ]);
    }

    /**
     *  @test
     *  The correct renters cancel count is in the response
     */
    public function the_correct_renters_cancel_count_is_in_the_response()
    {
        $this->createSmallDatabase();

        $user = User::has('orders')->where('host', 0)->first();

        $this->actingAs($user);

        $response = $this->json('GET', '/api/dashboard/renter-stats');

        $response->assertJsonFragment([
            'cancelCount' => $user->getCancellationsAsRenter()->count()
        ]);
    }

    /**
     *  @test
     *  The correct renters order count is in the response
     */
    public function the_correct_renters_order_count_is_in_the_response()
    {
        $this->createSmallDatabase();

        $user = User::has('orders')->where('host', 0)->first();

        $this->actingAs($user);

        $response = $this->json('GET', '/api/dashboard/renter-stats');

        $response->assertJsonFragment([
            'orderCount' => $user->orders->count()
        ]);
    }

    /**
     *  @test
     *  The correct renters order average is in the response
     */
    public function the_correct_renters_order_average_is_in_the_response()
    {
        $this->createSmallDatabase();

        $user = User::has('orders')->where('host', 0)->first();

        $this->actingAs($user);

        $response = $this->json('GET', '/api/dashboard/renter-stats');

        $response->assertJsonFragment([
            'orderAverage' => $user->orders->average('total')
        ]);
    }

    /**
     *  @test
     *  The renters bookings by month are correct and in the response
     */
    public function the_renters_bookings_by_month_are_correct_and_in_the_response()
    {
        $this->createSmallDatabase();

        $user = User::has('orders')->where('host', 0)->first();

        $this->actingAs($user);

        $bookingsByMonth = $this->bookingsByMonth($user->orders);

        $this->json('GET', '/api/dashboard/renter-stats')
            ->assertJsonFragment($bookingsByMonth);
    }

    /**
     *  @test
     *  The renters totals by month are correct and in the response
     */
    public function the_renters_totals_by_month_are_correct_and_in_the_response()
    {
        $this->createSmallDatabase();

        $user = User::has('orders')->where('host', 0)->first();

        $this->actingAs($user);

        $totalsByMonth = $this->totalsByMonth($user->orders);

        $this->json('GET', '/api/dashboard/renter-stats')
            ->assertJsonFragment($totalsByMonth);
    }

    /**
     *  @test
     *  The renters recent bookings are in the json response
     */
    public function the_renters_recent_bookings_are_in_the_json_response()
    {
        $this->createSmallDatabase();

        $user = User::has('orders')->where('host', 0)->first();

        $this->actingAs($user);

        $bookings = Booking::with('vehicle.vehicleModel.vehicleMake')
            ->whereIn('order_id', $user->orders->pluck('id'))
            ->orderBy('created_at', 'DESC')
            ->limit(4)
            ->get();

        foreach ($bookings as $b) {
            $this->json('GET', '/api/dashboard/renter-stats')
                ->assertJsonFragment([
                    'booking' => [
                        'id' => $b['id'],
                        'from' => $b['from'],
                        'to' => $b['to'],
                        'price_day' => $b['price_day'],
                        'price_total' => $b['price_total'],
                        'created_at' => $b['created_at']
                    ]
                ]);
        }
    }
}
