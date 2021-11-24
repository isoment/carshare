<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserReviewsRenterResource extends JsonResource
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
            'renterReview' => [
                'id' => $this->renterReview->id,
                'rating' => $this->renterReview->rating,
                'content' => $this->renterReview->content
            ],
            'renter' => [
                'id' => $this->renterReview->user->id,
                'name' => $this->renterReview->user->name,
                'image' => $this->renterReview->user->profile->image
            ],
            'vehicle' => [
                'year' => $this->vehicle->year,
                'model' => $this->vehicle->vehicleModel->model,
                'make' => $this->vehicle->vehicleModel->vehicleMake->make,
                'featured_image' => $this->vehicle->featured_image
            ]
        ];
    }
}
