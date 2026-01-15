<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        // 1. Giả lập giỏ hàng (Danh sách sản phẩm đã thêm)
        $cartItems = [
            [
                'id' => 1,
                'name' => 'Apple iPad Mini',
                'model' => 'G2356', // Mã sản phẩm
                'price' => 299,
                'quantity' => 2,
                'image' => 'img/product-1.png'
            ],
            [
                'id' => 3,
                'name' => 'Sony WH-1000XM5',
                'model' => 'S-XM5',
                'price' => 348,
                'quantity' => 1,
                'image' => 'img/product-3.png'
            ],
            [
                'id' => 4,
                'name' => 'Apple Watch Series 9',
                'model' => 'W-S9',
                'price' => 399,
                'quantity' => 1,
                'image' => 'img/product-4.png'
            ],
        ];

        // 2. Tính toán tổng tiền
        $subTotal = 0;
        foreach ($cartItems as $item) {
            $subTotal += $item['price'] * $item['quantity'];
        }

        $shipping = 3.00; // Phí ship cố định
        $total = $subTotal + $shipping;

        // 3. Danh mục cho menu (để tránh lỗi layout)
        $categories = [
            ['id' => 1, 'name' => 'Accessories', 'count' => 3],
            ['id' => 2, 'name' => 'Electronics', 'count' => 5],
        ];

        return view('cart', compact('cartItems', 'subTotal', 'shipping', 'total', 'categories'));
    }

    // 2. Hàm thêm vào giỏ hàng
    public function addToCart($id)
    {
        // A. Dữ liệu sản phẩm gốc (Vì chưa có DB nên phải khai báo lại để tra cứu)
        $allProducts = [
            1 => ['id' => 1, 'name' => 'iPhone 15 Pro Max', 'price' => 1200, 'image' => 'img/product-1.png', 'model' => 'Pro Max'],
            2 => ['id' => 2, 'name' => 'MacBook Air M2', 'price' => 999, 'image' => 'img/product-2.png', 'model' => 'M2 Chip'],
            3 => ['id' => 3, 'name' => 'Sony WH-1000XM5', 'price' => 348, 'image' => 'img/product-3.png', 'model' => 'Noise Cancel'],
            4 => ['id' => 4, 'name' => 'Apple Watch Series 9', 'price' => 399, 'image' => 'img/product-4.png', 'model' => 'Series 9'],
            5 => ['id' => 5, 'name' => 'Samsung Galaxy S24', 'price' => 899, 'image' => 'img/product-5.png', 'model' => 'S24'],
            6 => ['id' => 6, 'name' => 'Dell XPS 15 9530', 'price' => 1400, 'image' => 'img/product-6.png', 'model' => 'XPS 15'],
            7 => ['id' => 7, 'name' => 'Canon EOS R6 Mark II', 'price' => 2499, 'image' => 'img/product-7.png', 'model' => 'R6 II'],
            8 => ['id' => 8, 'name' => 'iPad Air 5 M1', 'price' => 559, 'image' => 'img/product-8.png', 'model' => 'M1'],
        ];

        // Tìm sản phẩm theo ID
        if (!isset($allProducts[$id])) {
            abort(404);
        }
        $product = $allProducts[$id];

        // B. Lấy giỏ hàng hiện tại từ Session
        $cart = session()->get('cart', []);

        // C. Kiểm tra xem sản phẩm đã có trong giỏ chưa
        if (isset($cart[$id])) {
            // Nếu có rồi thì tăng số lượng
            $cart[$id]['quantity']++;
        } else {
            // Nếu chưa có thì thêm mới
            $cart[$id] = [
                "id" => $product['id'], // Lưu lại ID để sau này dùng xóa/sửa
                "name" => $product['name'],
                "quantity" => 1,
                "price" => $product['price'],
                "image" => $product['image'],
                "model" => $product['model'] ?? 'N/A'
            ];
        }

        // D. Lưu lại vào Session
        session()->put('cart', $cart);

        // E. Quay lại trang trước đó và thông báo thành công
        return redirect()->back()->with('success', 'Đã thêm sản phẩm vào giỏ hàng!');
    }

    // 3. Xóa sản phẩm khỏi giỏ
    public function remove($id)
    {
        $cart = session()->get('cart');

        if (isset($cart[$id])) {
            unset($cart[$id]); // Xóa sản phẩm khỏi mảng
            session()->put('cart', $cart); // Lưu lại session
        }

        return redirect()->back()->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng!');
    }

    // 4. Trang thanh toán (Checkout)
    public function checkout()
    {
        // 1. Lấy giỏ hàng
        $cartItems = session()->get('cart', []);

        // Nếu giỏ hàng trống thì đá về trang Shop
        if (count($cartItems) == 0) {
            return redirect()->route('shop');
        }

        // 2. Tính toán lại tổng tiền (Logic giống hàm index)
        $subTotal = 0;
        foreach ($cartItems as $item) {
            $subTotal += $item['price'] * $item['quantity'];
        }

        $shipping = 3.00;
        $total = $subTotal + $shipping;

        // 3. Trả về view checkout
        return view('checkout', compact('cartItems', 'subTotal', 'shipping', 'total'));
    }

    // 5. Xử lý đặt hàng (Khi bấm nút Place Order)
    public function placeOrder(Request $request)
    {
        // A. (Tùy chọn) Validate dữ liệu nhập vào
        $request->validate([
            'first_name' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
            'address' => 'required',
        ]);

        // B. Xử lý lưu đơn hàng vào Database (Bước này làm sau khi có DB)
        // Ví dụ: Order::create($request->all());

        // C. Xóa giỏ hàng trong Session
        session()->forget('cart');

        // D. Chuyển hướng sang trang thành công
        return redirect()->route('cart.orderSuccess')->with('success', 'Đơn hàng đã được đặt thành công!');
    }

    // 6. Trang thông báo thành công
    public function orderSuccess()
    {
        return view('order-success');
    }
}
