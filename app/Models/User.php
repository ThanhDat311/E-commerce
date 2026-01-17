<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use App\Models\Order;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'role_id',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /* =====================
        RELATIONSHIPS
    ====================== */

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function authLogs()
    {
        return $this->hasMany(AuthLog::class);
    }

    public function behaviorProfile()
    {
        return $this->hasOne(UserBehaviorProfile::class);
    }

    public function productRatings()
    {
        return $this->hasMany(ProductRating::class);
    }

    public function hasPermission(string $permissionSlug): bool
    {
        return $this->role
            ->permissions
            ->contains('slug', $permissionSlug);
    }

    public function isAdmin(): bool
    {
        return $this->role?->name === 'Admin';
    }
}
