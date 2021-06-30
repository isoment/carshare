<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RenterReview extends Model
{
    use HasFactory;

    protected $keyType = 'string';

    /**
     *  Relationship to booking
     */
    public function booking()
    {
        return $this->hasOne(Booking::class, 'renter_review_key');
    }

    /**
     *  Relationship to user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
