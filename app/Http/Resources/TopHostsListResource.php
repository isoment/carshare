<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TopHostsListResource extends JsonResource
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
            'id' => $this['id'],
            'host_name' => $this['host_name'],
            'host_avatar' => $this['host_avatar'],
            'created_at' => $this['created_at'],
            'rating' => $this['rating'],
            'content' => $this['content'],
            'host_review_count' => $this['host_review_count'],
            'renter_name' => $this['renter_name']
        ];
    }
}
