<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // Danh sách đơn hàng của người dùng đang đăng nhập
    public function index(Request $request)
    {
        // Lấy đơn hàng của user hiện tại, sắp xếp mới nhất
        $orders = Order::where('user_id', Auth::id())
                        ->orderBy('created_at', 'desc')
                        ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    // Xem chi tiết đơn hàng (Cần kiểm tra chính chủ)
    public function show($id)
    {
        $order = Order::with('orderItems.product')
                    ->where('id', $id)
                    ->where('user_id', Auth::id()) // Bảo mật: Chỉ xem được đơn của chính mình
                    ->firstOrFail();

        return view('orders.show', compact('order'));
    }
}