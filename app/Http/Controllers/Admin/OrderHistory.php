<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderHistory extends Model
{
    use HasFactory;

    // Khai báo các cột được phép thêm dữ liệu
    protected $fillable = [
        'order_id',
        'user_id',
        'action',      // Ví dụ: 'Update', 'Cancel'
        'description', // Chi tiết thay đổi
    ];

    /**
     * Quan hệ: Lịch sử này thuộc về đơn hàng nào?
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Quan hệ: Ai là người thực hiện hành động này? (Admin/User)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}