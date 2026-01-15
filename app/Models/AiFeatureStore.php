<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiFeatureStore extends Model
{
    protected $fillable = [
        'auth_log_id',
        'velocity_check',
        'ip_reputation_score',
        'device_trust_score',
        'behavior_anomaly_score',
        'label',
    ];

    public function authLog()
    {
        return $this->belongsTo(AuthLog::class);
    }
}
