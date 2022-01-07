<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserBookingShowHostResource extends JsonResource
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
            'userIs' => 'host',
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
            'user' => [
                'name' => $this->order->user->name,
                'image' => $this->order->user->profile->image,
                'location' => $this->order->user->profile->location,
                'languages' => $this->order->user->profile->languages,
                'work' => $this->order->user->profile->work,
                'school' => $this->order->user->profile->school,
                'about' => $this->order->user->profile->about,
                'created_at' => $this->order->user->created_at
            ]
        ];
    }
}
