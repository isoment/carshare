<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShowLicenseResource extends JsonResource
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
            'city' => $this->city,
            'dob' => $this->dob,
            'expiration' => $this->expiration,
            'issued' => $this->issued,
            'number' => $this->number,
            'state' => $this->state,
            'street' => $this->street,
            'zip' => $this->zip
        ];
    }
}
