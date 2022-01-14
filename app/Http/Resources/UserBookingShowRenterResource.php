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
                'created_at' => $this->vehicle->created_at
            ],
            'order' => [
                'id' => $this->order->id,
                'total' => $this->order->total,
                'transaction_id' => $this->order->payment_method,
                'created_at' => $this->order->created_at
            ],
            'user' => [
                'id' => $this->vehicle->user->id,
                'name' => $this->vehicle->user->name,
                'image' => $this->vehicle->user->profile->image,
                'location' => $this->vehicle->user->profile->location,
                'languages' => $this->vehicle->user->profile->languages,
                'work' => $this->vehicle->user->profile->work,
                'school' => $this->vehicle->user->profile->school,
                'about' => $this->vehicle->user->profile->about,
                'created_at' => $this->vehicle->user->created_at
            ]
        ];
    }
}
