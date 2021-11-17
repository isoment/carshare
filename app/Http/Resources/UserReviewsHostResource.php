<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserReviewsHostResource extends JsonResource
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
                'order_id' => $this->order_id,
                'vehicle_id' => $this->vehicle_id
            ],
            'hostReview' => [
                'id' => $this->hostReview->id,
                'rating' => $this->hostReview->rating,
                'content' => $this->hostReview->content
            ],
            'host' => [
                'id' => $this->hostReview->user->id,
                'name' => $this->hostReview->user->name
            ],
            'vehicle' => [
                'year' => $this->vehicle->year,
                'model' => $this->vehicle->vehicleModel->model,
                'make' => $this->vehicle->vehicleModel->vehicleMake->make
            ]
        ];
    }
}
