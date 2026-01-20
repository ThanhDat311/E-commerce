<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderHistory; // Đảm bảo bạn đã tạo Model này
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * 1. DANH SÁCH ĐƠN HÀNG (Kèm bộ lọc)
     */
    public function index(Request $request)
    {
        // Khởi tạo query với User để hiển thị tên khách
        $query = Order::with('user');

        // --- BỘ LỌC TÌM KIẾM ---
        
        // 1. Tìm theo từ khóa (Mã đơn, Tên, Email)
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('id', 'like', "%$keyword%")
                  ->orWhere('first_name', 'like', "%$keyword%")
                  ->orWhere('last_name', 'like', "%$keyword%")
                  ->orWhere('email', 'like', "%$keyword%");
            });
        }

        // 2. Lọc theo Trạng thái đơn hàng
        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }

        // 3. Lọc theo Trạng thái thanh toán
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // 4. Lọc theo Ngày tháng
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Sắp xếp đơn mới nhất lên đầu và phân trang
        $orders = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * 2. XEM CHI TIẾT ĐƠN HÀNG
     */
    public function show($id)
    {
        // Eager load: Lấy kèm sản phẩm, user, và lịch sử đơn hàng
        $order = Order::with(['orderItems.product', 'user', 'histories.user'])->findOrFail($id);
        
        return view('admin.orders.show', compact('order'));
    }

    /**
     * 3. CẬP NHẬT ĐƠN HÀNG
     */
    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        
        // Mảng lưu lại các thay đổi để ghi log
        $actionDescription = [];

        // --- XỬ LÝ LOGIC CẬP NHẬT ---

        // 1. Cập nhật Trạng thái đơn hàng (order_status)
        if ($request->has('status') && $request->status != $order->order_status) {
            $actionDescription[] = "Changed status from '{$order->order_status}' to '{$request->status}'";
            $order->order_status = $request->status;
        }

        // 2. Cập nhật Trạng thái thanh toán (payment_status)
        if ($request->has('payment_status') && $request->payment_status != $order->payment_status) {
            $actionDescription[] = "Payment status changed to '{$request->payment_status}'";
            $order->payment_status = $request->payment_status;
        }

        // 3. Cập nhật Vận chuyển (Carrier & Tracking)
        if ($request->filled('tracking_number')) {
             // Chỉ ghi log nếu tracking thay đổi
             if ($order->tracking_number != $request->tracking_number) {
                 $actionDescription[] = "Updated tracking info: {$request->tracking_number}";
             }
             $order->tracking_number = $request->tracking_number;
             $order->shipping_carrier = $request->shipping_carrier;
        }
        
        // 4. Ghi chú nội bộ (Admin Note)
        if ($request->filled('admin_note')) {
            $order->admin_note = $request->admin_note;
        }

        // Lưu vào Database
        $order->save();

        // --- GHI LỊCH SỬ (LOG) ---
        if (!empty($actionDescription)) {
            // Kiểm tra xem Model OrderHistory có tồn tại không để tránh lỗi
            if (class_exists(OrderHistory::class)) {
                OrderHistory::create([
                    'order_id' => $order->id,
                    'user_id' => Auth::id(), // Người thực hiện (Admin đang đăng nhập)
                    'action' => 'Update',
                    'description' => implode('. ', $actionDescription),
                ]);
            }
        }

        return back()->with('success', 'Order updated successfully.');
    }
}