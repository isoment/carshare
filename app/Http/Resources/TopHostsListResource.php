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
            'host_name' => $this['name'],
            'host_avatar' => $this['profile']['image'],
            'host_review_count' => $this['host_review_count'],
            'member_since' => $this['created_at'],
            'rating' => $this['latest_host_review']['rating'],
            'content' => $this['latest_host_review']['content'],
            'review_date' => $this['latest_host_review']['updated_at'],
            'renter_name' => $this['latest_host_review']['booking']['order']['user']['name'],
        ];
    }
}
