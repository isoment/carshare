<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

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
     *  Relationship to cancellations
     */
    public function cancellations()
    {
        return $this->hasMany(Cancellation::class);
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

    /**
     *  Determine if the vehicle is available during the specified dates
     * 
     *  @param string $from
     *  @param string $to
     *  @return boolean
     */
    public function isAvailable(string $from, string $to) : bool
    {
        $fromFormatted = Carbon::parse($from)->toDateString();
        $toFormatted = Carbon::parse($to)->toDateString();

        return $this->bookings()->betweenDates($fromFormatted, $toFormatted)
            ->count() === 0;
    }

    /**
     *  Calculate the price of the vehicle
     * 
     *  @param string $from
     *  @param string $to
     *  @return array
     */
    public function calculatePrice(string $from, string $to)
    {
        $days = Carbon::parse($from)->diffInDays(Carbon::parse($to)) + 1;

        $totalPrice = $days * $this->price_day;

        return [
            'days' => $days,
            'price_day' => $this->price_day,
            'total' => $totalPrice
        ];
    }

    /**
     *  Determine if the vehicle has images
     * 
     *  @return bool
     */
    public function vehicleHasImages() : bool
    {
        return $this->vehicleImages->count() !== 0;
    }

    /**
     *  The date ranges that the vehicle is booked. Don't want dates before today
     * 
     *  @return array
     */
    public function bookedDates() : array
    {
        $dates = [];

        $bookings = $this->bookings->where('to', '>=', Carbon::now());

        foreach ($bookings as $booking) {
            array_push($dates, [
                'start' => Carbon::parse($booking->from)->format('m/d/Y'),
                'end' => Carbon::parse($booking->to)->format('m/d/Y')
            ]);
        }

        return $dates;
    }
}
