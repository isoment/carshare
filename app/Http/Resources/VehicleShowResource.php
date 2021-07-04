<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VehicleShowResource extends JsonResource
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
            'vehicle_model' => $this['vehicle_model']['model'],
            'vehicle_make' => $this['vehicle_model']['vehicle_make']['make'],
            'year' => $this['year'],
            'price' => $this['price_day'],
            'description' => $this['description'],
            'doors' => $this['doors'],
            'seats' => $this['seats'],
            'vehicle_images' => $this['vehicle_images'],
            'host_name' => $this['host']['name'],
            'top_host' => $this['host']['top_host'],
            'member_since' => $this['host']['created_at'],
            'host_total_trips' => $this['host_total_trips'],
            'host_rating' => $this['host_rating'],
            'vehicle_rating' => $this['vehicle_rating'],
            'vehicle_review_count' => $this['vehicle_review_count'],
            'vehicle_trip_count' => $this['vehicle_trip_count'],
            'vehicle_reviews' => $this['vehicle_reviews']
        ];
    }
}
