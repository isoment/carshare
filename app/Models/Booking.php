<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    /**
     *  Relationship to vehicle
     */
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     *  Relationship to user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     *  Relationship to renter reviews
     */
    public function renterReviews()
    {
        return $this->hasMany(RenterReview::class);
    }

    /**
     *  Relationship to host reviews
     */
    public function hostReviews()
    {
        return $this->hasMany(HostReview::class);
    }
}
