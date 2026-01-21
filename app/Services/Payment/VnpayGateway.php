<?php

namespace App\Services\Payment;

use App\Models\Order;
use Illuminate\Http\Request;

class VnpayGateway implements PaymentGatewayInterface
{
    public function process(Order $order): array
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');

        $vnp_Url = config('services.vnpay.url');
        $vnp_Returnurl = route('payment.vnpay.callback');
        $vnp_TmnCode = config('services.vnpay.tmn_code');
        $vnp_HashSecret = config('services.vnpay.hash_secret');

        $vnp_TxnRef = $order->id;
        
        // [FIX 1] Bỏ ký tự đặc biệt # để tránh lỗi encode
        $vnp_OrderInfo = "Thanh toan don hang " . $order->id; 
        
        $vnp_OrderType = "billpayment";
        $vnp_Amount = intval($order->total * 100);
        $vnp_Locale = 'vn';
        
        // [FIX 2] QUAN TRỌNG: Ép cứng IPv4 cho môi trường Local
        // VNPay Sandbox thường từ chối IP ::1 của Laragon
        $vnp_IpAddr = '127.0.0.1'; 

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef
        );

        // Lọc bỏ dữ liệu rỗng
        foreach ($inputData as $key => $value) {
            if (is_null($value) || $value === '') {
                unset($inputData[$key]);
            }
        }

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

        return [
            'success' => true,
            'is_redirect' => true,
            'redirect_url' => $vnp_Url
        ];
    }

    public function verify(Request $request): array
    {
        $inputData = $request->toArray();
        $vnp_SecureHash = $inputData['vnp_SecureHash'] ?? '';
        
        if (isset($inputData['vnp_SecureHash'])) {
            unset($inputData['vnp_SecureHash']);
        }
        if (isset($inputData['vnp_SecureHashType'])) {
            unset($inputData['vnp_SecureHashType']);
        }

        ksort($inputData);
        $i = 0;
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $vnp_HashSecret = config('services.vnpay.hash_secret');
        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        if ($secureHash === $vnp_SecureHash) {
            if (isset($inputData['vnp_ResponseCode']) && $inputData['vnp_ResponseCode'] == '00') {
                return [
                    'success' => true,
                    'order_id' => $inputData['vnp_TxnRef'],
                    'amount' => $inputData['vnp_Amount'] / 100,
                    'transaction_no' => $inputData['vnp_TransactionNo'],
                    'message' => 'Giao dịch thành công'
                ];
            } else {
                return [
                    'success' => false,
                    'order_id' => $inputData['vnp_TxnRef'],
                    'message' => 'Giao dịch lỗi (Code: ' . ($inputData['vnp_ResponseCode'] ?? 'Unknown') . ')'
                ];
            }
        } else {
            return [
                'success' => false,
                'message' => 'Chữ ký không hợp lệ'
            ];
        }
    }
}