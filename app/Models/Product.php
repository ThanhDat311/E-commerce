<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'sku',
        'price',
        'sale_price',
        'stock_quantity',
        'image_url',
        'is_new',
        'is_featured',
        'description',
    ];

    // --- THÊM ĐOẠN NÀY ---
    protected $casts = [
        'is_new' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function ratings()
    {
        return $this->hasMany(ProductRating::class);
    }

    public function averageRating()
    {
        return round($this->ratings()->avg('rating'), 1);
    }

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }
}
