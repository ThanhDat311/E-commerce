<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // Danh sách đơn hàng của tôi
    public function index()
    {
        // Lấy đơn hàng của user đang đăng nhập, mới nhất lên đầu, phân trang 5 đơn
        $orders = Auth::user()->orders()->with('items.product')->latest()->paginate(5);
        
        return view('orders.index', compact('orders'));
    }

    // Chi tiết một đơn hàng cụ thể
    public function show($id)
    {
        // Tìm đơn hàng của user này (findOrFail để bảo mật, không cho xem đơn người khác)
        $order = Auth::user()->orders()->with('items.product')->findOrFail($id);
        
        return view('orders.show', compact('order'));
    }
}