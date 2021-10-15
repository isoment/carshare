<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class VehicleMake extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     *  Relationship to vehicle model
     */
    public function vehicleModels()
    {
        return $this->hasMany(VehicleModel::class);
    }

    /**
     *  Get a listing of vehicle models for a given make
     * 
     *  @param Illuminate\Database\Eloquent\Builder $query
     *  @param string $make
     * 
     *  @return Illuminate\Support\Collection
     */
    public function scopeVehicleModelsForMake(Builder $query, string $make) : Collection
    {
        $make = $query->where('make', $make)->first();

        return $make->vehicleModels->pluck('model');
    }
}
