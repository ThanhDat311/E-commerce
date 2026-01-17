<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiFeatureStore extends Model
{
    use HasFactory;

    protected $table = 'ai_feature_store'; // Định danh rõ tên bảng

    protected $fillable = [
        'auth_log_id',
        'order_id',
        'total_amount',
        'ip_address',
        'risk_score',
        'reasons',
        'label',
    ];

    // Tự động chuyển JSON trong DB thành Mảng trong PHP
    protected $casts = [
        'reasons' => 'array',
        'risk_score' => 'float',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}