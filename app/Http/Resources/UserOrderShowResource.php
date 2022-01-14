<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserOrderShowResource extends JsonResource
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
            'order_placed' => $this->created_at,
            'transaction_id' => $this->payment_method,
            'total_price' => $this->total,
            'bookings' => UserOrderShowBookingsResource::collection($this->bookings)
        ];
    }
}
