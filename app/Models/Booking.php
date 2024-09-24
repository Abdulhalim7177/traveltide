<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory;

    // app/Models/Booking.php

protected $fillable = [
    'user_id',
    'trip_id',
    'seat_number',
    'unique_identifier',
];


    protected static function booted()
    {
        static::creating(function ($booking) {
            $maxSeatNumber = self::where('trip_id', $booking->trip_id)->max('seat_number') ?? 0;
            $booking->seat_number = $maxSeatNumber + 1;
        });
    }

    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
