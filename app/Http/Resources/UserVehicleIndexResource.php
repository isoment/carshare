<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserVehicleIndexResource extends JsonResource
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
            'active' => $this->active,
            'created_at' => $this->created_at,
            'model' => $this->vehicleModel->model,
            'make' => $this->vehicleModel->vehicleMake->make,
        ];
    }
}
