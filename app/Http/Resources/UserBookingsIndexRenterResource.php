<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserBookingsIndexRenterResource extends JsonResource
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
            'booking' => [
                'id' => $this->id,
                'from' => $this->from,
                'to' => $this->to,
                'price_day' => $this->price_day,
                'price_total' => $this->price_total
            ],
            'vehicle' => [
                'model' => $this->vehicle->vehicleModel->model,
                'make' => $this->vehicle->vehicleModel->vehicleMake->make,
                'year' => $this->vehicle->year,
                'image' => $this->vehicle->featured_image,
                'active' => $this->vehicle->active
            ],
            'order' => [
                'id' => $this->order->id,
                'total' => $this->order->total,
                'transaction_id' => $this->order->transaction_id
            ]
        ];
    }
}
