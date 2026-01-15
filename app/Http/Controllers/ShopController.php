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
     * Hiển thị chi tiết sản phẩm
     */
    public function show($id)
    {
        // 1. Tìm sản phẩm trong DB theo ID
        // findOrFail: Nếu không tìm thấy id sẽ tự động trả về trang lỗi 404
        $product = Product::findOrFail($id);

        // 2. Lấy sản phẩm liên quan (Cùng danh mục, khác ID hiện tại)
        $relatedProducts = Product::where('category', $product->category)
            ->where('id', '!=', $id)
            ->take(4)
            ->get();

        // 3. Dữ liệu cho Sidebar (Copy từ hàm index để sidebar hiển thị đầy đủ)
        $categories = [
            ['id' => 1, 'name' => 'Accessories', 'count' => 3],
            ['id' => 2, 'name' => 'Electronics', 'count' => 5],
            ['id' => 3, 'name' => 'Laptops', 'count' => 2],
            ['id' => 4, 'name' => 'Mobiles', 'count' => 8],
        ];

        $colors = [
            ['name' => 'Gold', 'count' => 1],
            ['name' => 'White', 'count' => 1],
        ];

        // Sản phẩm nổi bật cho sidebar trang chi tiết
        $featuredProducts = Product::where('rating', 5)->take(3)->get();

        // 4. Truyền sang View
        return view('detail', compact('product', 'relatedProducts', 'categories', 'colors', 'featuredProducts'));
    }
}
