<?php

namespace App\Services\Payment;

// [FIX 1] Import đúng Model Order (Thay vì App\Services\Payment\Order)
use App\Models\Order; 

// [FIX 2] Import class Request của Laravel
use Illuminate\Http\Request; 

class CodGateway implements PaymentGatewayInterface
{
    public function process(Order $order): array
    {
        // COD không cần xử lý bên thứ 3, mặc định là thành công bước khởi tạo
        return [
            'success' => true,
            'is_redirect' => false,
            'message' => 'Order placed successfully with COD.',
            'redirect_url' => null
        ];
    }

    public function verify(Request $request): array
    {
        // COD không có callback, trả về false nếu vô tình bị gọi
        return [
            'success' => false,
            'message' => 'COD method does not support callback verification.'
        ];
    }
}