<?php

namespace App\Repositories\Eloquent;

use App\Models\Order;
use App\Models\OrderItem;
use App\Repositories\Interfaces\OrderRepositoryInterface;

class OrderRepository implements OrderRepositoryInterface
{
    /**
     * Tạo đơn hàng mới
     */
    public function createOrder(array $data)
    {
        return Order::create($data);
    }

    /**
     * Tạo chi tiết đơn hàng (Order Item)
     */
    public function createOrderItem(array $data)
    {
        return OrderItem::create($data);
    }

    /**
     * Lấy danh sách đơn hàng (Phân trang)
     */
    public function getAllOrders($perPage = 10)
    {
        return Order::latest()->paginate($perPage);
    }

    /**
     * Tìm đơn hàng theo ID
     */
    public function find($id)
    {
        return Order::findOrFail($id);
    }
    
    public function getProductsByVendor($vendorId, $perPage = 10)
    {
        return []; 
    }
}