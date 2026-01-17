<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        // 1. Thay vì dùng mảng cứng, ta query từ Database
        // Lấy 8 sản phẩm mới nhất (sắp xếp theo ngày tạo)
        // Hoặc lấy theo cờ is_new: Product::where('is_new', true)->take(8)->get();
        $newProducts = Product::latest()
                        ->take(8)
                        ->get();

        $featuredProducts = Product::inRandomOrder()->take(8)->get();

        return view('home', compact('newProducts', 'featuredProducts'));
    }
}