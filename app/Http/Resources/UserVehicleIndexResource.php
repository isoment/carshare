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
            'year' => $this->year,
            'price_day' => $this->price_day,
            'active' => $this->active,
            'created_at' => $this->created_at,
            'model' => $this->vehicleModel->model,
            'make' => $this->vehicleModel->vehicleMake->make,
            'image' => $this->vehicleImages->first()->image
        ];
    }
}
