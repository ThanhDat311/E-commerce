<?php

namespace App\Http\Controllers;

use App\Services\OrderService;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * Xử lý khi user được redirect từ VNPay về website
     */
    public function vnpayCallback(Request $request)
    {
        try {
            // Gọi Service để xử lý logic update DB
            // Tham số 'vnpay' giúp Factory biết dùng Strategy nào để verify
            $result = $this->orderService->handlePaymentCallback('vnpay', $request);

            if ($result['success']) {
                // Redirect về trang Success kèm thông báo
                return redirect()->route('checkout.success')
                    ->with('success', 'Thanh toán thành công!')
                    ->with('order_id', $result['order_id']);
            } else {
                // Redirect về trang lỗi hoặc trang thanh toán lại
                return redirect()->route('checkout.index') // Hoặc route hiển thị lỗi
                    ->withErrors(['error' => 'Thanh toán thất bại: ' . $result['message']]);
            }
        } catch (Exception $e) {
            Log::error('Vnpay Callback Error: ' . $e->getMessage());
            return redirect()->route('home')
                ->withErrors(['error' => 'Lỗi hệ thống khi xử lý thanh toán.']);
        }
    }
}