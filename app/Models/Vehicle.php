<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     *  Relationship to user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     *  Relationship to vehicle model
     */
    public function vehicleModel()
    {
        return $this->belongsTo(VehicleModel::class);
    }

    /**
     *  Relationship to bookings
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     *  Relationship to vehicle images
     */
    public function vehicleImages()
    {
        return $this->hasMany(VehicleImages::class);
    }

    /**
     *  Get all the vehicles including make, model and trip count
     */
    public function scopeAllVehicles($query)
    {
        return $query->with('vehicleModel.vehicleMake')
            ->withCount('bookings')
            ->paginate();
    }

    /**
     *  Get the max and min price for vehicles in the system
     *  rounded to nearest ten
     */
    public static function priceRange()
    {
        return [
            'max' => ceil(Vehicle::max('price_day') / 10) * 10,
            'min' => floor(Vehicle::min('price_day') / 10) * 10
        ];
    }

}
