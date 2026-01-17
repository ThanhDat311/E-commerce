<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; 
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'note',
        'order_status',
        'payment_status',
        'payment_method',
        'total', // Đã đổi từ total_amount sang total
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function aiFeature()
    {
        return $this->hasOne(AiFeatureStore::class, 'order_id');
    }
}