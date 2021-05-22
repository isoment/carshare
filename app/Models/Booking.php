<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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
        return $this->belongsTo(RenterReview::class);
    }

    /**
     *  Relationship to host reviews
     */
    public function hostReviews()
    {
        return $this->belongsTo(HostReview::class);
    }

    /**
     *  Create a user and host review key when a new Booking
     *  is created.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function($booking) {
            $booking->renter_review_key = Str::uuid();
            $booking->host_review_key = Str::uuid();
        });
    }
}
