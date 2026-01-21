<?php

namespace App\Repositories\Interfaces;

interface OrderRepositoryInterface
{
    public function createOrder(array $data);
    public function createOrderItem(array $data);
    public function getAllOrders($perPage = 10);

    public function find($id);

    public function getProductsByVendor($vendorId, $perPage = 10);
}