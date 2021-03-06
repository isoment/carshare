<?php

namespace App\Models;

use Carbon\Carbon;
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
     *  Determine if a user the renter of a booking
     *  
     *  @param User $user
     *  @return bool
     */
    public function userIsRenterOfBooking(User $user) : bool
    {
        return (int) $this->order->user_id === $user->id;
    }

    /**
     *  Determine if a user is the host for a booking
     * 
     *  @param User $user
     *  @return bool
     */
    public function userIsHostOfBooking(User $user) : bool
    {
        return (int) $this->vehicle->user_id === $user->id;
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
     *  Determine charges when a renter initiates a refund
     * 
     *  A guest can cancel the day before the booking and get 
     *  a full refund. If the guest cancels outside of the free period they
     *  are charged as follows...
     * 
     *  Trip length is 2 days or less 50% refund.
     *  Trip length greater than 2 days, full refund minus one day.
     */
    public function renterInitiatedRefund() : array
    {
        $today = Carbon::now();

        $lastDayForFreeCancellation = Carbon::parse($this->from)
            ->subDays(1);

        if ($today->lessThanOrEqualTo($lastDayForFreeCancellation)) {
            return [
                'type' => 'Full refund',
                'amount' => $this->price_total,
            ];
        }

        if ($today->greaterThanOrEqualTo($lastDayForFreeCancellation)) {
            if ($this->bookingTotalDays() > 2) {
                return [
                    'type' => 'Full refund minus one day',
                    'amount' => $this->totalMinusPricePerDay(
                        $this->price_total, 
                        $this->price_day
                    ),
                ];
            } else {
                $paymentHalf = $this->discountPercent(50, $this->price_total);

                return [
                    'type' => '50% refund',
                    'amount' => $paymentHalf,
                ];
            }
        }
    }

    /**
     *  Get the total length of the booking in days
     * 
     *  @return int
     */
    public function bookingTotalDays() : int
    {
        $from = Carbon::parse($this->from);
        $to = Carbon::parse($this->to);

        return $from->diffInDays($to) + 1;
    }

    /**
     *  Check if the booking has already started
     *  
     *  @return bool
     */
    public function hasAlreadyStarted() : bool
    {
        $from = Carbon::parse($this->from);

        return Carbon::now()->greaterThanOrEqualTo($from) ? true : false;
    }

    /**
     *  Delete the reviews associated with the booking
     */
    public function deleteReviews() : void
    {
        RenterReview::destroy($this->renter_review_key);
        HostReview::destroy($this->host_review_key);
    }

    /**
     *  Create a review of the renter for this booking
     */
    public function createRenterReview()
    {
        $this->renterReview()->create([
            'id' => $this->renter_review_key,
            'user_id' => $this->order->user_id
        ]);
    }

    /**
     *  Create a review of the host for this booking
     */
    public function createHostReview()
    {
        $this->hostReview()->create([
            'id' => $this->host_review_key,
            'user_id' => $this->vehicle->user_id
        ]);
    }

    /**
     *  @return string
     */
    private function totalMinusPricePerDay() : string
    {
        return bcsub($this->price_total, $this->price_day, 2);
    }

    /**
     *  @param int $percent the percent to discount as a whole integer between 1 and 100
     *  @param string $amount the amount to calculate the discount on
     *  @return string
     */
    private function discountPercent(int $percent, string $amount) : string
    {
        if ($percent >= 1 && $percent <= 100) {
            $discount = $percent * 0.01;
        } else {
            $discount = "1";
        }

        return bcmul($amount, (string) $discount, 2);
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
