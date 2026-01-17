<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use App\Services\OrderService; // Đừng quên import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Import Auth để dùng Auth::id()

class CartController extends Controller
{
    // FIX LỖI 2: Khai báo Property
    protected $cartService;
    protected $orderService;

    // FIX LỖI 2: Inject cả 2 Service vào đây
    public function __construct(CartService $cartService, OrderService $orderService)
    {
        $this->cartService = $cartService;
        $this->orderService = $orderService;
    }

    // ... (Giữ nguyên các hàm index, addToCart, remove...)

    public function index()
    {
        $data = $this->cartService->getCartDetails();
        return view('cart', $data);
    }

    public function addToCart(Request $request, $id)
    {
        // Lấy số lượng từ URL (mặc định là 1 nếu không truyền)
        $quantity = $request->input('quantity', 1);

        // Gọi Service xử lý
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
        if (count($data['cartItems']) == 0) {
            return redirect()->route('shop');
        }
        return view('checkout', $data);
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string',
            'address' => 'required|string',
        ]);

        try {
            $this->orderService->processCheckout($request->all(), Auth::id());
            return redirect()->route('cart.orderSuccess')->with('success', 'Order placed successfully!');
        } catch (\Exception $e) {
            dd($e->getMessage());
            return redirect()->back()->with('error', 'Checkout failed. Please try again.');
        }
    }

    public function orderSuccess()
    {
        return view('order-success');
    }
}
