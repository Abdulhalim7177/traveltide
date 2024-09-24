<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'start_location',
        'destination',
        'trip_time',
        'status',
        'vehicle_id',
        'price',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
