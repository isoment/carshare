<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
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
     *  Get a collection of users bookings with host reviews
     * 
     *  @return Illuminate\Pagination\LengthAwarePaginator
     */
    public function getCompletedHostReviews() : LengthAwarePaginator
    {
        $usersOrders = $this->orders->pluck('id');

        // return Booking::with(['hostReview.user.profile', 'vehicle.vehicleModel.vehicleMake'])
        //     ->whereHas('hostReview', function($query) {

        //         $query->whereNotNull('rating');

        //     })->whereIn('order_id', $usersOrders)->paginate(5);

        // Just temporary for building up our paginator component
        return Booking::with(['hostReview.user.profile', 'vehicle.vehicleModel.vehicleMake'])
            ->whereIn('order_id', $usersOrders)->paginate(2);
    }
}
