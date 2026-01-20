<?php

namespace App\Repositories\Interfaces;

interface OrderRepositoryInterface
{
    public function createOrder(array $data);
    public function createOrderItem(array $data);
    
    // Thêm dòng này
    public function getAllOrders($perPage = 10);
}