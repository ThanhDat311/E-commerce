<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserBehaviorProfile extends Model
{
    protected $fillable = [
        'user_id',
        'frequent_ips',
        'frequent_locations',
        'trusted_devices',
        'avg_login_time_start',
        'avg_login_time_end',
        'last_updated',
    ];

    protected $casts = [
        'frequent_ips' => 'array',
        'frequent_locations' => 'array',
        'trusted_devices' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
