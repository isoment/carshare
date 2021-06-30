<?php

namespace App\Models;

use Database\Seeders\HostReviewSeeder;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

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
     *  Relationship to bookings
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
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
}
