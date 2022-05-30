<?php

namespace App\Models;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Pagination\LengthAwarePaginator;
use Laravel\Cashier\Billable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'host'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     *  Relationship to vehicles
     */
    public function vehicles() 
    {
        return $this->hasMany(Vehicle::class);
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

    /**
     *  Relationship to profile
     */
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    /**
     *  Relationship to drivers license
     */
    public function driversLicense()
    {
        return $this->hasOne(DriversLicense::class);
    }

    /**
     *  Relationship to orders
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     *  Determine if user is free to book on the given date range
     * 
     *  @param string $from
     *  @param string $to
     * 
     *  @return boolean
     */
    public function hasNoBooking(string $from, string $to) : bool
    {
        $fromFormatted = Carbon::parse($from)->toDateString();
        $toFormatted = Carbon::parse($to)->toDateString();

        $orders = auth()->user()->orders->pluck('id');

        return Booking::whereIn('order_id', $orders)
            ->where('to', '>=', $fromFormatted)
            ->where('from', '<=', $toFormatted)->count() === 0;
    }

    /**
     *  Get a collection of a users bookings
     * 
     *  @return Illuminate\Database\Eloquent\Collection
     */
    public function getBookings() : Collection
    {
        $usersOrders = $this->orders->pluck('id');

        return Booking::whereIn('order_id', $usersOrders)->get();
    }

    /**
     *  The cancellations the user initiated as host
     * 
     *  @return Illuminate\Database\Eloquent\Collection
     */
    public function getCancellationsAsHost() : Collection
    {
        $usersVehicles = $this->vehicles->pluck('id');

        return Cancellation::whereIn('vehicle_id', $usersVehicles)
            ->where('who_cancelled', 'host')
            ->get();
    }

    /**
     *  The cancellations the user initiated as renter
     * 
     *  @return Illuminate\Database\Eloquent\Collection
     */
    public function getCancellationsAsRenter() : Collection
    {
        $usersOrders = $this->orders->pluck('id');

        return Cancellation::whereIn('order_id', $usersOrders)
            ->where('who_cancelled', 'renter')
            ->get();
    }

    /**
     *  An array of dates of users bookings
     * 
     *  @return array
     */
    public function bookingDates() : array
    {
        $dates = [];

        $bookings = $this->getBookings()->where('to', '>=', Carbon::now());

        foreach ($bookings as $booking) {
            array_push($dates, [
                'start' => Carbon::parse($booking->from)->format('m/d/Y'),
                'end' => Carbon::parse($booking->to)->format('m/d/Y')
            ]);
        }

        return $dates;
    }

    /**
     *  An array of each individual date the user has a booking
     * 
     *  @return array
     */
    public function individualBookingDates() : array
    {
        $dates = [];

        $bookings = $this->getBookings()->where('to', '>=', Carbon::now());

        foreach ($bookings as $booking) {
            $period = CarbonPeriod::create($booking->from, $booking->to);

            foreach ($period as $date) {
                array_push($dates, $date->format('m/d/Y'));
            }
        }

        return $dates;
    }

    /**
     *  Get a collection of bookings of a users vehicles
     * 
     *  @return Illuminate\Database\Eloquent\Collection
     */
    public function getVehicleBookings() : Collection
    {
        $usersVehicles = $this->vehicles->pluck('id');

        return Booking::whereIn('vehicle_id', $usersVehicles)->get();
    }

    /**
     *  Get a collection of users bookings with completed host reviews
     * 
     *  @return Illuminate\Pagination\LengthAwarePaginator
     */
    public function getCompletedReviewsOfHost() : LengthAwarePaginator
    {
        $usersOrders = $this->orders->pluck('id');

        return Booking::with(['hostReview.user.profile', 'vehicle.vehicleModel.vehicleMake'])
            ->whereHas('hostReview', function($query) {

                $query->whereNotNull('rating');

            })->whereIn('order_id', $usersOrders)->paginate(4);
    }

    /**
     *  Get a collection of users bookings with incomplete host reviews
     * 
     *  @return Illuminate\Pagination\LengthAwarePaginator
     */
    public function getUncompletedReviewsOfHost() : LengthAwarePaginator
    {
        $usersOrders = $this->orders->pluck('id');

        return Booking::with(['hostReview.user.profile', 'vehicle.vehicleModel.vehicleMake'])
            ->whereHas('hostReview', function($query) {

                $query->whereNull('rating');

            })->whereIn('order_id', $usersOrders)
                ->where('to', '<=', Carbon::now())
                ->paginate(4);
    }

    /**
     *  Get a collection of users who booked your vehicles where
     *  the reviews are complete
     * 
     *  @return Illuminate\Pagination\LengthAwarePaginator
     */
    public function getCompletedReviewsOfRenter() : LengthAwarePaginator
    {
        $usersVehicles = $this->vehicles->pluck('id');

        return Booking::with(['renterReview.user.profile', 'vehicle.vehicleModel.vehicleMake'])
            ->whereHas('renterReview', function($query) {

                $query->whereNotNull('rating');

            })->whereIn('vehicle_id', $usersVehicles)->paginate(4);
    }

    /**
     *  Get a collection of users who booked your vehicles where
     *  the reviews are incomplete
     * 
     *  @return Illuminate\Pagination\LengthAwarePaginator
     */
    public function getUncompletedReviewsOfRenter() : LengthAwarePaginator
    {
        $usersVehicles = $this->vehicles->pluck('id');

        return Booking::with(['renterReview.user.profile', 'vehicle.vehicleModel.vehicleMake'])
            ->whereHas('renterReview', function($query) {

                $query->whereNull('rating');

            })->whereIn('vehicle_id', $usersVehicles)
                ->where('to', '<=', Carbon::now())
                ->paginate(4);
    }

    /**
     *  Create a new relationship to get a single recent host review
     */
    public function latestHostReview()
    {
        return $this->belongsTo(HostReview::class);
    }

    /**
     *  Dynamically load the latest host review based on the above relationship
     *  as well as the user who left it.
     */
    public function scopeWithLatestHostReview(Builder $query) : void
    {
        $query->addSelect(['latest_host_review_id' => HostReview::select('id')
            ->whereColumn('user_id', 'users.id')
            ->where('rating', '>=', 3)
            ->latest()
            ->take(1)
        ])->with('latestHostReview.booking.order.user');
    }
}
