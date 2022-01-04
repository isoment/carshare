<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class UserBookingShowRenterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'userIs' => 'renter',
            'booking' => [
                'id' => $this->id,
                'from' => $this->from,
                'to' => $this->to,
                'price_day' => $this->price_day,
                'price_total' => $this->price_total,
                'created_at' => $this->created_at
            ],
            'vehicle' => [
                'id' => $this->vehicle->id,
                'image' => $this->vehicle->featured_image,
                'make' => $this->vehicle->vehicleModel->vehicleMake->make,
                'model' => $this->vehicle->vehicleModel->model,
                'year' => $this->vehicle->year,
            ],
            'order' => [
                'id' => $this->order->id,
                'total' => $this->order->total,
                'transaction_id' => $this->order->transaction_id,
                'created_at' => $this->order->created_at
            ]
        ];
    }
}
