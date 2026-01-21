<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Interfaces\ProductRepositoryInterface; // Import Interface

class ShopController extends Controller
{
    protected $productRepository;

    // Inject Repository qua Constructor
    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function index(Request $request)
    {
        // 1. Lấy dữ liệu (ProductRepository đã sửa limit = 6)
        $products = $this->productRepository->getFilteredProducts($request->all(), 6);

        // ... (Code sidebar cũ giữ nguyên) ...
        $categories =$this->productRepository->getCategoriesWithProductCount();
        $colors = [['name' => 'Gold', 'count' => 1], ['name' => 'White', 'count' => 1]];
        $featuredProducts = \App\Models\Product::withAvg('ratings', 'rating')
            ->orderByDesc('ratings_avg_rating')
            ->take(3)->get();

        return view('shop', compact('products', 'categories', 'colors', 'featuredProducts'));
    }

    public function show($id)
    {
        // 1. Lấy chi tiết sản phẩm (chỉ 1 query Eager Loading)
        $product = $this->productRepository->getProductDetails($id);

        // 2. Lấy sản phẩm liên quan
        $relatedProducts = $this->productRepository->getRelatedProducts($product);

        // 3. Dữ liệu Sidebar
        $categories = $this->productRepository->getCategoriesWithProductCount();
        $colors = [['name' => 'Gold', 'count' => 1], ['name' => 'White', 'count' => 1]];

        // Featured (Code cũ)
        $featuredProducts = \App\Models\Product::withAvg('ratings', 'rating')
            ->orderByDesc('ratings_avg_rating')
            ->take(3)
            ->get();

        return view('detail', compact('product', 'relatedProducts', 'categories', 'colors', 'featuredProducts'));
    }
}
