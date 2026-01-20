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
        'total'
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function histories()
    {
        return $this->hasMany(OrderHistory::class)->orderBy('created_at', 'desc');
    }

    public function getStatusAttribute()
    {
        return $this->order_status;
    }
    
    public function getTotalPriceAttribute()
    {
        return $this->total;
    }

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