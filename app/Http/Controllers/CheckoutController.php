<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Services\OrderService;
use App\Services\CartService; // [FIX 1] Import CartService
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class CheckoutController extends Controller
{
    protected OrderService $orderService;
    protected CartService $cartService; // [FIX 2] Khai báo property

    // [FIX 3] Inject CartService vào Constructor
    public function __construct(OrderService $orderService, CartService $cartService)
    {
        $this->orderService = $orderService;
        $this->cartService = $cartService;
    }

    public function show()
    {
        // Bây giờ $this->cartService đã được khởi tạo và có thể sử dụng
        $cartData = $this->cartService->getCartDetails(); 
        
        return view('checkout', $cartData);
    }

    public function process(CheckoutRequest $request)
    {
        try {
            // Prepare customer data from request
            $customerData = $request->validated();
            
            // Get User ID if authenticated
            $userId = Auth::id();

            // Call the Service
            // Lưu ý: processCheckout bây giờ trả về mảng ['order' => ..., 'payment_result' => ...]
            $result = $this->orderService->processCheckout($customerData, $userId);

            $order = $result['order'];
            $paymentResult = $result['payment_result'];

            // Nếu cổng thanh toán yêu cầu redirect (như VNPay)
            if ($paymentResult['is_redirect'] && !empty($paymentResult['redirect_url'])) {
                return redirect()->to($paymentResult['redirect_url']);
            }

            // Nếu là COD hoặc thanh toán tại chỗ
            return redirect()->route('checkout.success')->with('order_id', $order->id);

        } catch (Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function success()
    {
        if (!session('order_id')) {
            return redirect()->route('home');
        }
        return view('order-success');
    }
}