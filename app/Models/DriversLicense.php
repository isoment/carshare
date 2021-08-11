<?php

namespace App\Models;

use App\Http\Resources\ShowLicenseResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriversLicense extends Model
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
     *  Show the license for the authenticated user
     */
    public function scopeShowLicense($query)
    {
        return $query->where('user_id', auth()->id())->firstOrFail();
    }
}
