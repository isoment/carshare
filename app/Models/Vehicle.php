<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

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
     *  Get all the vehicles including make, model and trip count
     */
    public function scopeAllVehicles($query)
    {
        return $query->with('vehicleModel.vehicleMake')
            ->withCount('bookings')
            ->paginate();
    }

    /**
     * Get the vehicles available on the dates passed in
     */
    public function scopeIndexOfAvailableVehicles($query, $from, $to)
    {
        return $query->whereDoesntHave('bookings', function($query) use ($from, $to) {
            $query->betweenDates($from, $to);
        })->with('vehicleModel.vehicleMake')
            ->withCount('bookings')
            ->paginate();
    }
}
