<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HostReview extends Model
{
    use HasFactory;

    /**
     *  Relationship to booking
     */
    public function booking()
    {
        return $this->hasOne(Booking::class, 'host_review_key');
    }

    /**
     *  Relationship to user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
