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
     *  Relationship to order
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
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
     * 
     *  @param Illuminate\Database\Eloquent\Builder $query
     *  @param int $vehicleId
     *  @return float
     */
    public function scopeCalculateVehicleRating(Builder $query, int $vehicleId) : float
    {
        $reviewKeys = $query->where('vehicle_id', $vehicleId)
            ->get()
            ->pluck('host_review_key');

        $ratings = HostReview::whereIn('id', $reviewKeys)
            ->whereNotNull('rating')
            ->get()
            ->pluck('rating');

        // To prevent divisible by error, just return 0
        if ($ratings->count() === 0) {
            return 0;
        }

        return round($ratings->sum() / $ratings->count(), 1);
    }

    /**
     *  We need to check if there is a match in bookings during the dates
     *  that we specify, this is kind of hard to visualize but we can do
     *  it using two where queries.
     * 
     *  @param Illuminate\Database\Eloquent\Builder $query
     *  @param string $from
     *  @param string $to
     *  @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeBetweenDates(Builder $query, string $from, string $to) : Builder
    {
        return $query->where('to', '>=', $from)
            ->where('from', '<=', $to);
    }

    /**
     *  Create a user and host review key when a new Booking
     *  is created.
     *
     *  @return void
     */
    protected static function boot() : void
    {
        parent::boot();

        static::creating(function($booking) {
            $booking->renter_review_key = Str::uuid();
            $booking->host_review_key = Str::uuid();
        });
    }
}
