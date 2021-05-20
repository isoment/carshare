<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleMake extends Model
{
    use HasFactory;

    /**
     *  Relationship to vehicle model
     */
    public function vehicleModels()
    {
        return $this->hasMany(VehicleModel::class);
    }
}
