<?php

namespace App\Services\Payment;
use Illuminate\Http\Request;

use App\Models\Order;

interface PaymentGatewayInterface
{
    /**
     * Xử lý thanh toán.
     * Trả về mảng chứa trạng thái và URL redirect (nếu là online payment).
     */
    public function process(Order $order): array;

    /**
     * Xác thực dữ liệu callback từ cổng thanh toán.
     * Trả về kết quả xác thực (success, order_id, amount, v.v.)
     */
    public function verify(Request $request): array;
}