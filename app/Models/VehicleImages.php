<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleImages extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     *  Relationship to vehicle
     */
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     *  Determine if the given image belongs to a users vehicle
     * 
     *  @return bool
     */
    public function imageBelongsToUsersVehicle() : bool
    {
        $userId = current_user()->id;

        return $this->vehicle->user_id === $userId ? true : false;
    }
}
