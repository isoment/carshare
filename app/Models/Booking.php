<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class Booking extends Model
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
     *  Relationship to user
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     *  Relationship to renter reviews
     */
    public function renterReview()
    {
        return $this->belongsTo(RenterReview::class, 'renter_review_key');
    }

    /**
     *  Relationship to host reviews
     */
    public function hostReview()
    {
        return $this->belongsTo(HostReview::class, 'host_review_key');
    }

    /**
     *  Calculate vehicle rating
     */
    public function scopeCalculateVehicleRating(Builder $query, $vehicleId)
    {
        $bookings = $query->where('vehicle_id', $vehicleId)->get();

        $hostReviews = $bookings->each(function($booking) {
            $booking->hostReview;
        });

        $ratings = $hostReviews->pluck('hostReview.rating');

        return round($ratings->sum() / $ratings->count(), 1);
    }

    /**
     *  We need to check if there is a match in bookings during the dates
     *  that we specify, this is kind of hard to visualize but we can do
     *  it using two where queries.
     */
    public function scopeBetweenDates(Builder $query, $from, $to)
    {
        return $query->where('to', '>=', $from)
            ->where('from', '<=', $to);
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
