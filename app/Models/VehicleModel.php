<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleModel extends Model
{
    use HasFactory;

    /**
     *  Relationship to vehicle makes
     */
    public function vehicleMake()
    {
        return $this->belongsTo(VehicleMake::class);
    }
}
