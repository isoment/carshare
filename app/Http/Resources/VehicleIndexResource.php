<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VehicleIndexResource extends JsonResource
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
            'featured_image' => $this->featured_image,
            'year' => $this->year,
            'price_day' => $this->price_day,
            'model' => $this->vehicleModel->model,
            'vehicle_make' => $this->vehicleModel->vehicleMake->make,
            'bookings_count' => $this->bookings_count,
            'image' => $this->vehicleImages->first()->image,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude
        ];
    }
}
