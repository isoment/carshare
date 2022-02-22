<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
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
     *  A 404 error is returned if the renter have no bookings
     */
    public function a_404_error_is_returned_if_the_renter_has_no_bookings()
    {
        $this->createSmallDatabase();

        $user = User::where('host', 0)->first();

        $this->actingAs($user);

        $usersBookingIds = $user->getBookings()->pluck('id');
        
        Booking::whereIn('id', $usersBookingIds)->each(function($booking) {
            $booking->delete();
        });

        $this->json('GET', '/api/dashboard/renter-stats')
            ->assertStatus(404)
            ->assertSee('No bookings');
    }

    /**
     *  @test
     *  The response from the renter stats api endpoint has the correct structure
     */
    public function the_response_from_the_renter_stats_api_endpoint_has_the_correct_json_structure()
    {
        $this->createSmallDatabase();

        $this->clearCache();

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

        $this->clearCache();

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

        $this->clearCache();

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

        $this->clearCache();

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

        $this->clearCache();

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

        $this->clearCache();

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

        $this->clearCache();

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

        $this->clearCache();

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

        $this->clearCache();

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

    /**
     *  @test
     *  An unauthenticated user gets a 401 response from host stats api endpoint
     */
    public function an_unauthenticated_user_gets_a_401_response_from_host_stats_api_endpoint()
    {
        $this->json('GET', '/api/dashboard/host-stats')
            ->assertStatus(401);
    }

    /**
     *  @test
     *  A user who is not a host gets a 403 error response
     */
    public function a_user_who_is_not_a_host_gets_a_403_error_response()
    {
        $this->createSmallDatabase();

        $user = User::where('host', 0)->first();

        $this->actingAs($user);

        $this->json('GET', '/api/dashboard/host-stats')
            ->assertStatus(403)
            ->assertSee('You cannot access this');
    }

    /**
     *  @test
     *  A 404 error is returned if the hosts vehicles have no bookings
     */
    public function a_404_error_is_returned_if_the_hosts_vehicles_have_no_bookings()
    {
        $this->createSmallDatabase();

        $user = User::where('host', 1)->first();

        $this->actingAs($user);

        $usersVehiclesBookingIds = $user->getVehicleBookings()->pluck('id');
        
        Booking::whereIn('id', $usersVehiclesBookingIds)->each(function($booking) {
            $booking->delete();
        });

        $this->json('GET', '/api/dashboard/host-stats')
            ->assertStatus(404)
            ->assertSee('No bookings');
    }

    /**
     *  @test
     *  The response from the host stats api endpoint has the correct structure
     */
    public function the_response_from_the_host_stats_api_endpoint_has_the_correct_json_structure()
    {
        $this->createSmallDatabase();

        $this->clearCache();

        $user = User::has('vehicles')->where('host', 1)->first();

        $this->actingAs($user);

        $response = $this->json('GET', '/api/dashboard/host-stats');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'basic' => [
                    'totalEarned', 'bookingCount', 'cancelCount', 'longestBooking', 'bookingAverage'
                ],
                'highestBookedMonths' => [
                    'month', 'count'
                ],
                'durationOfBookings' => [
                    'booking', 'duration'
                ],
                'earningsByMonth' => [
                    'month', 'total'
                ],
                'popularVehicles' => [
                    'vehicle', 'count'
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
     *  The correct hosts total earned is in the response 
     */
    public function the_correct_hosts_total_earned_is_in_the_response()
    {
        $this->createSmallDatabase();

        $this->clearCache();

        $user = User::has('orders')->where('host', 1)->first();

        $this->actingAs($user);

        $response = $this->json('GET', '/api/dashboard/host-stats');

        $response->assertJsonFragment([
            'totalEarned' => $user->getVehicleBookings()->sum('price_total')
        ]);
    }

    /**
     *  @test
     *  The correct hosts booking count is in the response 
     */
    public function the_correct_hosts_booking_count_is_in_the_response()
    {
        $this->createSmallDatabase();

        $this->clearCache();

        $user = User::has('orders')->where('host', 1)->first();

        $this->actingAs($user);

        $response = $this->json('GET', '/api/dashboard/host-stats');

        $response->assertJsonFragment([
            'bookingCount' => $user->getVehicleBookings()->count()
        ]);
    }

    /**
     *  @test
     *  The correct hosts cancel count is in the response 
     */
    public function the_correct_hosts_cancel_count_is_in_the_response()
    {
        $this->createSmallDatabase();

        $this->clearCache();

        $user = User::has('orders')->where('host', 1)->first();

        $this->actingAs($user);

        $response = $this->json('GET', '/api/dashboard/host-stats');

        $response->assertJsonFragment([
            'cancelCount' => $user->getCancellationsAsHost()->count()
        ]);
    }

    /**
     *  @test
     *  The correct hosts longest booking is in the response 
     */
    public function the_correct_hosts_longest_booking_is_in_the_response()
    {
        $this->createSmallDatabase();

        $this->clearCache();

        $user = User::has('orders')->where('host', 1)->first();

        $this->actingAs($user);

        $response = $this->json('GET', '/api/dashboard/host-stats');

        // Add all the booking durations to the $durations array
        foreach ($user->getVehicleBookings() as $booking) {
            $durations[$booking['id']] = $booking->bookingTotalDays();
        }

        $response->assertJsonFragment([
            'longestBooking' => max($durations)
        ]);
    }

    /**
     *  @test
     *  The correct hosts booking average is in the response 
     */
    public function the_correct_hosts_booking_average_is_in_the_response()
    {
        $this->createSmallDatabase();

        $this->clearCache();

        $user = User::has('orders')->where('host', 1)->first();

        $this->actingAs($user);

        $response = $this->json('GET', '/api/dashboard/host-stats');

        // Add all the booking durations to the $durations array
        foreach ($user->getVehicleBookings() as $booking) {
            $durations[$booking['id']] = $booking->bookingTotalDays();
        }

        $avg = array_sum($durations) / count($durations);

        $avgRounded =  round($avg, 0, PHP_ROUND_HALF_UP);

        $response->assertJsonFragment([
            'bookingAverage' => $avgRounded
        ]);
    }

    /**
     *  @test
     *  The hosts highest booked months are correct and in the response
     */
    public function the_hosts_highest_booked_months_are_correct_and_in_the_response()
    {
        $this->createSmallDatabase();

        $this->clearCache();

        $user = User::has('orders')->where('host', 1)->first();

        $this->actingAs($user);

        $highestBookedMonths = $this->hostHighestBookedMonths($user->vehicles);

        $this->json('GET', '/api/dashboard/host-stats')
            ->assertJsonFragment($highestBookedMonths);
    }

    /**
     *  @test
     *  The hosts duration of bookings are correct and in the response
     */
    public function the_hosts_duration_of_bookings_are_correct_and_in_the_response()
    {
        $this->createSmallDatabase();

        $this->clearCache();

        $user = User::has('orders')->where('host', 1)->first();

        $this->actingAs($user);

        $bookings = $user->getVehicleBookings();

        foreach ($bookings as $booking) {
            $durations[$booking['id']] = $booking->bookingTotalDays();
        }

        $bookingDurations = collect($durations)
            ->take(-14)
            ->toArray();

        $this->json('GET', '/api/dashboard/host-stats')
            ->assertJsonFragment([
                'durationOfBookings' => [
                    'booking' => array_keys($bookingDurations),
                    'duration' => array_values($bookingDurations)
                ],
            ]);
    }

    /**
     *  @test
     *  The hosts earnings by month are correct and in the response
     */
    public function the_hosts_earnings_by_month_are_correct_and_in_the_response()
    {
        $this->createSmallDatabase();

        $this->clearCache();

        $user = User::has('orders')->where('host', 1)->first();

        $this->actingAs($user);

        $earningsByMonth = $this->totalsByMonth($user->vehicles, 'vehicle_id', 5, 'earningsByMonth');

        $this->json('GET', '/api/dashboard/host-stats')
            ->assertJsonFragment($earningsByMonth);
    }

    /**
     *  @test
     *  The hosts popular vehicles are correct and in the response
     */
    public function the_hosts_popular_vehicles_are_correct_and_in_the_response()
    {
        $this->createSmallDatabase();

        $this->clearCache();

        $user = User::has('orders')->where('host', 1)->first();

        $this->actingAs($user);

        foreach ($user->vehicles as $vehicle) {
            $id = $vehicle->id;
            $year = $vehicle->year;
            $model = $vehicle->vehicleModel->model;

            $array["{$year} {$model} {$id}"] = $vehicle->bookings->count(); 
        }

        $popularVehicles = array_splice($array, -5, 5);

        $this->json('GET', '/api/dashboard/host-stats')
            ->assertJsonFragment([
                'popularVehicles' => [
                    'vehicle' => array_keys($popularVehicles),
                    'count' => array_values($popularVehicles)
                ],
            ]);
    }

    /**
     *  @test
     *  The hosts vehicles recent bookings are in the json response
     */
    public function the_hosts_vehicles_recent_bookings_are_in_the_json_response()
    {
        $this->createSmallDatabase();

        $this->clearCache();

        $user = User::has('orders')->where('host', 1)->first();

        $this->actingAs($user);

        $bookings = Booking::with('vehicle.vehicleModel.vehicleMake')
            ->whereIn('vehicle_id', $user->vehicles->pluck('id'))
            ->orderBy('created_at', 'DESC')
            ->limit(4)
            ->get();

        foreach ($bookings as $b) {
            $this->json('GET', '/api/dashboard/host-stats')
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

    /**
     *  @test
     *  The renter statistics are cached in redis
     */
    public function the_renter_statistics_are_cached_in_redis()
    {
        $this->createSmallDatabase();

        $this->clearCache();

        $this->assertFalse(Cache::store('redis')->has('test-key'));

        $user = User::has('orders')->where('host', 0)->first();

        $this->actingAs($user);

        $this->json('GET', '/api/dashboard/renter-stats');

        $this->assertTrue(Cache::store('redis')->has('test-key'));
    }

    /**
     *  @test
     *  The host statistics are cached in redis
     */
    public function the_host_statistics_are_cached_in_redis()
    {
        $this->createSmallDatabase();

        $this->clearCache();

        $this->assertFalse(Cache::store('redis')->has('test-key'));

        $user = User::has('orders')->where('host', 1)->first();

        $this->actingAs($user);

        $this->json('GET', '/api/dashboard/host-stats');

        $this->assertTrue(Cache::store('redis')->has('test-key'));

        // Clear testing keys from redis on final test
        $this->clearCache();
    }
}
