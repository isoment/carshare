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
            'id' => $this['host_id'],
            'host_name' => $this['host_name'],
            'host_avatar' => $this['host_avatar'],
            'host_review_count' => $this['host_review_count'],
            'created_at' => $this['host_review']['created_at'],
            'rating' => $this['host_review']['rating'],
            'content' => $this['host_review']['content'],
            'renter_name' => $this['renter_name']
        ];
    }
}
