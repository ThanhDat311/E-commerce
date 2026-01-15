<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuthLog extends Model
{
    protected $fillable = [
        'user_id',
        'session_id',
        'ip_address',
        'device_fingerprint',
        'user_agent',
        'geo_location',
        'risk_score',
        'risk_level',
        'auth_decision',
        'is_successful',
    ];

    protected $casts = [
        'geo_location' => 'array',
        'is_successful' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function aiFeature()
    {
        return $this->hasOne(AiFeatureStore::class);
    }
}
