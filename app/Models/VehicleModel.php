<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleModel extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     *  Relationship to vehicle makes
     */
    public function vehicleMake()
    {
        return $this->belongsTo(VehicleMake::class);
    }

    /**
     *  Relationship to vehicle
     */
    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }
}
