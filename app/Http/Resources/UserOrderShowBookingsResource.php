<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserOrderShowBookingsResource extends JsonResource
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
            'id' => $this->id,
            'from' => $this->from,
            'to' => $this->to,
            'price_day' => $this->price_day,
            'price_total' => $this->price_total,
            'vehicle_image' => $this->vehicle->featured_image,
            'vehicle_year' => $this->vehicle->year,
            'vehicle_model' => $this->vehicle->vehicleModel->model,
            'vehicle_make' => $this->vehicle->vehicleModel->vehicleMake->make
        ];
    }
}
