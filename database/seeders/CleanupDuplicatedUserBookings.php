<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Seeder;

class CleanupDuplicatedUserBookings extends Seeder
{
    /**
     * Here we want to remove and duplicate bookings for a user that we may have
     * created in the BookingSeeder. Then remove any orders that don't have any bookings
     *
     * @return void
     */
    public function run()
    {
        User::all()->each(function($user) {
            $userOrderIds = $user->orders->pluck('id');
            $bookings = $user->getBookings();

            $bookings->each(function($booking) use ($userOrderIds) {
                // Need to compare this booking to all of the users others
                $bookingCheck = Booking::whereIn('order_id', $userOrderIds)
                    ->where('to', '>=', $booking->from)
                    ->where('from', '<=', $booking->to)
                    ->get();

                if ($bookingCheck->count() > 1) {
                    $removeFirst = $bookingCheck->reject(function($b) use ($bookingCheck) {
                        return $b->id === $bookingCheck[0]['id'];
                    });
                }

                if (isset($removeFirst)) {
                    foreach ($removeFirst as $b) {
                        $deleteMe = Booking::find($b->id);
                        $deleteMe->delete();
                    }
                }
            });
        });

        Order::all()->each(function($order) {
            if (count($order->bookings) === 0) {
                $o = Order::find($order->id);
                $o->delete();
            }
        });
    }
}
