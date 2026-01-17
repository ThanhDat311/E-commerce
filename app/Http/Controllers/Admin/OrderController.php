<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        // Eager Load 'aiFeature' để tránh lỗi N+1 Query
        // Chúng ta giả định Model Order có quan hệ hasOne với AiFeatureStore (sẽ khai báo ở bước 2)
        $orders = Order::with(['items', 'aiFeature'])
                    ->latest()
                    ->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * XEM CHI TIẾT ĐƠN HÀNG
     */
    public function show($id)
    {
        // Eager load sâu: Order -> Items -> Product
        $order = Order::with(['items.product', 'aiFeature'])->findOrFail($id);
        
        return view('admin.orders.show', compact('order'));
    }

    /**
     * XỬ LÝ TRẠNG THÁI (DUYỆT / HỦY)
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled'
        ]);

        $order = Order::findOrFail($id);
        
        // Cập nhật trạng thái
        $order->order_status = $request->status;
        $order->save();

        // TODO: Sau này sẽ thêm gửi email thông báo cho khách ở đây
        
        return redirect()->back()->with('success', 'Order status updated successfully!');
    }
}