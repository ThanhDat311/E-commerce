<?php

namespace App\Repositories\Eloquent;

use App\Models\Order;
use App\Models\OrderItem;
use App\Repositories\Interfaces\OrderRepositoryInterface;

class OrderRepository implements OrderRepositoryInterface
{
    public function createOrder(array $data)
    {
        return Order::create($data);
    }

    public function createOrderItem(array $data)
    {
        return OrderItem::create($data);
    }

    // Thực thi hàm getAllOrders
    public function getAllOrders($perPage = 10)
    {
        // Sử dụng Eager Loading 'items' và 'aiFeature' như logic cũ của Controller để tối ưu query
        // latest() để lấy đơn mới nhất trước
        return Order::with(['items', 'aiFeature'])
                    ->latest()
                    ->paginate($perPage);
    }
}