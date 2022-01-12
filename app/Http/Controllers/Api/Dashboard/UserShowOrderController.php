<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserOrderShowResource;
use App\Models\Order;
use Illuminate\Http\Request;

class UserShowOrderController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param int $orderId
     * @return \Illuminate\Http\Response
     */
    public function __invoke(int $orderId)
    {
        $order = Order::with('bookings.vehicle.vehicleModel.vehicleMake')
            ->where('id', $orderId)
            ->first();

        if ($order === null) {
            return response()->json('Booking not found', 404);
        }

        if ($order->user_id !== current_user()->id) {
            return response()->json('Unauthorized', 403);
        }

        return new UserOrderShowResource($order);
    }
}
