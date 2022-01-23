<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserBookingIndexHostResource extends JsonResource
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
                'id' => $this->vehicle->id,
                'image' => $this->vehicle->featured_image,
                'make' => $this->vehicle->vehicleModel->vehicleMake->make,
                'model' => $this->vehicle->vehicleModel->model,
                'year' => $this->vehicle->year,
                'active' => $this->vehicle->active
            ]
        ];
    }
}
