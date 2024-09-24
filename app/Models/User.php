<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Testing\Fluent\Concerns\Has;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    use HasRoles, Notifiable;

    public function canAccessPanel(Panel $panel): bool{
        return true;    
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    use HasRoles, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'password',
        'unique_identifier',
    ];

    protected static function boot()
{
    parent::boot();

    static::creating(function ($user) {
        if ($user->password) {
            $user->password = bcrypt($user->password);
        }
    });

    static::creating(function ($user) {
        // Generate unique identifier if not set
        if (empty($user->unique_identifier)) {
            do {
                $identifier = strtoupper(substr(md5(uniqid(rand(), true)), 0, 3)) . rand(1000, 9999);
            } while (self::where('unique_identifier', $identifier)->exists());

            $user->unique_identifier = $identifier;
        }
    });
}


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function isAdmin()
    {
        return $this->is_admin;
    }
}
