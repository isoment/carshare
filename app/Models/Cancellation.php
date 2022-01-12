<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cancellation extends Model
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
     *  Relationship to Order
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
