<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // 1. Tạo biến categories (Dữ liệu giả lập)
        $categories = [
            ['name' => 'Accessories', 'count' => 3],
            ['name' => 'Electronics', 'count' => 5],
            ['name' => 'Laptops', 'count' => 2],
            ['name' => 'Mobiles', 'count' => 8],
            ['name' => 'Smart TV', 'count' => 4],
        ];

        // 2. Dữ liệu sản phẩm mới (Mở rộng 8 sản phẩm)
    $newProducts = [
        [
            'id' => 1,
            'name' => 'iPhone 15 Pro Max',
            'category' => 'SmartPhone',
            'price' => 1200,
            'old_price' => 1299,
            'image' => 'img/product-1.png',
            'is_new' => true,
            'rating' => 5,
        ],
        [
            'id' => 2,
            'name' => 'MacBook Air M2',
            'category' => 'Laptop',
            'price' => 999,
            'old_price' => 1099,
            'image' => 'img/product-2.png',
            'is_new' => true,
            'rating' => 5,
        ],
        [
            'id' => 3,
            'name' => 'Sony WH-1000XM5',
            'category' => 'Audio',
            'price' => 348,
            'old_price' => null, // Không giảm giá
            'image' => 'img/product-3.png',
            'is_new' => false,
            'rating' => 4,
        ],
        [
            'id' => 4,
            'name' => 'Apple Watch Series 9',
            'category' => 'Smart Watch',
            'price' => 399,
            'old_price' => 450,
            'image' => 'img/product-4.png',
            'is_new' => true,
            'rating' => 4,
        ],
        [
            'id' => 5,
            'name' => 'Samsung Galaxy S24',
            'category' => 'SmartPhone',
            'price' => 899,
            'old_price' => 950,
            'image' => 'img/product-5.png',
            'is_new' => false,
            'rating' => 4,
        ],
        [
            'id' => 6,
            'name' => 'Dell XPS 15 9530',
            'category' => 'Laptop',
            'price' => 1400,
            'old_price' => 1600,
            'image' => 'img/product-6.png',
            'is_new' => false,
            'rating' => 5,
        ],
        [
            'id' => 7,
            'name' => 'Canon EOS R6 Mark II',
            'category' => 'Camera',
            'price' => 2499,
            'old_price' => 2699,
            'image' => 'img/product-7.png',
            'is_new' => true,
            'rating' => 5,
        ],
        [
            'id' => 8,
            'name' => 'iPad Air 5 M1',
            'category' => 'Tablet',
            'price' => 559,
            'old_price' => 599,
            'image' => 'img/product-8.png',
            'is_new' => false,
            'rating' => 4,
        ],
    ];

        // 3. Truyền THÊM biến 'categories' sang View
        return view('home', compact('newProducts', 'categories'));
    }
}