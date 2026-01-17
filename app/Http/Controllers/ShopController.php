<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; // [QUAN TRỌNG] Import Model Product

class ShopController extends Controller
{
    /**
     * Hiển thị trang danh sách sản phẩm (Shop)
     */
    public function index(Request $request)
    {
        // BƯỚC 1: Khởi tạo truy vấn (Mặc định là lấy tất cả sản phẩm)
        $query = Product::query();

        // BƯỚC 2: Kiểm tra xem người dùng có nhập từ khóa tìm kiếm không?
        // Hàm filled() kiểm tra biến có tồn tại VÀ không rỗng
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where('name', 'LIKE', "%{$keyword}%");
        }

        // BƯỚC 3: Kiểm tra xem người dùng có chọn sắp xếp không?
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                default:
                    $query->orderBy('id', 'desc'); // Mặc định nếu value lạ
                    break;
            }
        } else {
            // Nếu KHÔNG chọn gì cả, mặc định sắp xếp theo mới nhất (hoặc ID)
            $query->orderBy('id', 'desc');
        }

        // BƯỚC 4: Thực thi và phân trang
        // ->withQueryString() cực kỳ quan trọng: Giúp giữ lại keyword và sort khi bấm sang trang 2, 3...
        $products = $query->paginate(9)->withQueryString();

        // ... (Các dữ liệu phụ sidebar giữ nguyên) ...
        $categories = [['id' => 1, 'name' => 'Accessories', 'count' => 3], ['id' => 2, 'name' => 'Electronics', 'count' => 5], ['id' => 3, 'name' => 'Laptops', 'count' => 2], ['id' => 4, 'name' => 'Mobiles', 'count' => 8]];
        $colors = [['name' => 'Gold', 'count' => 1], ['name' => 'White', 'count' => 1]];
         $featuredProducts = Product::withAvg('ratings', 'rating')
        ->having('ratings_avg_rating', '=', 5)
        ->limit(3)
        ->get();


        return view('shop', compact('products', 'categories', 'colors', 'featuredProducts'));
    }

    /**
     * Hiển thị chi tiết sản phẩm với Gợi ý Thông minh
     */
    public function show($id)
    {
        // 1. Tìm sản phẩm hiện tại
        $product = Product::findOrFail($id);

        // 2. [SMART LOGIC] Tìm sản phẩm liên quan
        // Logic: Cùng danh mục + Giá nằm trong khoảng chênh lệch 30%
        $minPrice = $product->price * 0.7; // 70% giá
        $maxPrice = $product->price * 1.3; // 130% giá

        $relatedProducts = Product::where('category_id', $product->category_id) // Cùng Category
            ->where('id', '!=', $id) // Trừ chính nó ra
            ->whereBetween('price', [$minPrice, $maxPrice]) // Lọc theo phân khúc giá
            ->inRandomOrder() // Đảo ngẫu nhiên để tăng trải nghiệm khám phá
            ->take(4)
            ->get();

        // [FALLBACK] Nếu logic trên ra ít hơn 4 sản phẩm (do lọc giá quá kỹ)
        // Thì nới lỏng điều kiện: Chỉ cần cùng danh mục
        if ($relatedProducts->count() < 4) {
            $moreProducts = Product::where('category_id', $product->category_id)
                ->where('id', '!=', $id)
                ->whereNotIn('id', $relatedProducts->pluck('id')) // Không lấy trùng
                ->take(4 - $relatedProducts->count())
                ->get();
            
            // Gộp lại cho đủ danh sách
            $relatedProducts = $relatedProducts->merge($moreProducts);
        }

        // 3. Dữ liệu Sidebar (Giữ nguyên code cũ của bạn hoặc Refactor sau)
        $categories = [
            ['id' => 1, 'name' => 'Accessories', 'count' => 3],
            ['id' => 2, 'name' => 'Electronics', 'count' => 5],
            // ... (Code cũ)
        ];
        $colors = [['name' => 'Gold', 'count' => 1], ['name' => 'White', 'count' => 1]];

        $product = Product::with(['category', 'reviews.user'])->findOrFail($id);

        $featuredProducts = Product::withAvg('ratings', 'rating')
                    ->orderByDesc('ratings_avg_rating') // Sắp xếp điểm cao nhất xuống
                    ->take(3)
                    ->get(); 

        return view('detail', compact('product', 'relatedProducts', 'categories', 'colors', 'featuredProducts'));
    }
}
