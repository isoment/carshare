<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserDetailResourceWithLicense extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'host' => $this->host,
            'created_at' => $this->created_at,
            'profile' => [
                'id' => $this->profile->id,
                'location' => $this->profile->location,
                'languages' => $this->profile->languages,
                'work' => $this->profile->work,
                'school' => $this->profile->school,
                'about' => $this->profile->about,
                'phone' => $this->profile->phone,
                'image' => $this->profile->image,
                'created_at' => $this->profile->created_at
            ],
            'drivers_license' => [
                'state' => $this->driversLicense->state,
                'street' => $this->driversLicense->street,
                'city' => $this->driversLicense->city,
                'zip' => $this->driversLicense->zip,
                'verified' => $this->driversLicense->verified
            ]
        ];
    }
}
