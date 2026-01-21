<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Http\Request;
use App\Http\Requests\CheckoutRequest;
use Illuminate\Support\Facades\Auth; // Đảm bảo có dòng này

class CartController extends Controller
{
    protected $cartService;
    protected $orderService;

    public function __construct(CartService $cartService, OrderService $orderService)
    {
        $this->cartService = $cartService;
        $this->orderService = $orderService;
    }

    public function index()
    {
        $data = $this->cartService->getCartDetails();
        return view('cart', $data);
    }

    public function addToCart(Request $request, $id)
    {
        $quantity = $request->input('quantity', 1);
        $result = $this->cartService->addToCart($id, (int)$quantity);

        if (!$result['status']) {
            return redirect()->back()->with('error', $result['message']);
        }

        return redirect()->back()->with('success', $result['message']);
    }

    public function remove($id)
    {
        $this->cartService->removeFromCart($id);
        return redirect()->back()->with('success', 'Product removed successfully!');
    }

    public function checkout()
    {
        $data = $this->cartService->getCartDetails();

        if (empty($data['cartItems'])) {
            return redirect()->route('shop')->with('error', 'Giỏ hàng trống!');
        }

        return view('checkout', $data);
    }

    public function placeOrder(CheckoutRequest $request)
    {
        try {
            $this->orderService->processCheckout($request->validated(), Auth::id());

            return redirect()->route('cart.orderSuccess')->with('success', 'Đơn hàng đã được đặt thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }

    public function orderSuccess()
    {
        return view('order-success');
    }
}