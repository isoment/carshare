<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RenterReview extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    protected $fillable = ['rating', 'content'];

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

    /**
     *  Current users can leave a review
     * 
     *  @return bool
     */
    public function userCanLeaveReview() : bool
    {
        return (int) $this->booking->vehicle->user_id === current_user()->id;
    }
}
