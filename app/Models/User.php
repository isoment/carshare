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

    /**
     *  Get a random assorted listing of top hosts with a single associated
     *  host review.
     */
    public function scopeTopHostsList($query)
    {
        $collection = $query
            ->where('top_host', 1)
            ->join('host_reviews', 'users.id', '=', 'host_reviews.user_id')
            ->where('rating', '>=', 3)
            ->get(['users.*', 'host_reviews.id as review_id', 'host_reviews.rating', 'host_reviews.content']);

        // One get one review with the user info collection
        $unique = $collection->unique('id')->random(8);

        // We want to add the number of host reviews and also
        // the renters name for the review we selected above for the top host
        $hostReviewInfo = [];

        foreach ($unique as $u) {
            $test = HostReview::where('id', $u['review_id'])->first();

            $hostReviewToAdd = [
                'host_review_count' => HostReview::where('user_id', $u['id'])->count(),
                'renter_name' => $test->booking->user->name
            ];

            array_push($hostReviewInfo, $hostReviewToAdd);
        }

        // For each entry in the $unique array we need to create a new array
        // with the below information.
        $finalResult = array();
        $count = count($unique);

        for ($i = 0; $i < $count; $i++) {
            $finalResult[$i]['id'] = $unique[$i]['id'];
            $finalResult[$i]['host_name'] = $unique[$i]['name'];
            $finalResult[$i]['created_at'] = $unique[$i]['created_at'];
            $finalResult[$i]['rating'] = $unique[$i]['rating'];
            $finalResult[$i]['content'] = $unique[$i]['content'];
            $finalResult[$i]['review_id'] = $unique[$i]['review_id'];
            $finalResult[$i]['host_review_count'] = $hostReviewInfo[$i]['host_review_count'];
            $finalResult[$i]['renter_name'] = $hostReviewInfo[$i]['renter_name'];
        }

        return $finalResult;
    }
}
