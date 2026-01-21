<?php

namespace App\Repositories\Eloquent;

use App\Models\Product;
use App\Models\Category; 
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class ProductRepository implements ProductRepositoryInterface
{
    protected $model;

    public function __construct(Product $model)
    {
        $this->model = $model;
    }

    public function find($id) { return $this->model->find($id); }
    public function all() { return $this->model->all(); }
    public function findByIds(array $ids) { return $this->model->whereIn('id', $ids)->get(); }
    public function create(array $data) { return Product::create($data); }
    
    public function update(int $id, array $data)
    {
        $product = $this->find($id);
        if ($product) {
            $product->update($data);
            return $product;
        }
        return null;
    }

    public function delete(int $id)
    {
        $product = $this->find($id);
        return $product ? $product->delete() : false;
    }

    // --- IMPLEMENTATION CÁC HÀM MỚI ---

    public function getFilteredProducts(array $filters, int $perPage = 6)
    {
        $query = $this->model->query();

        // 1. Filter by Keyword
        if (!empty($filters['keyword'])) {
            $query->where('name', 'LIKE', "%{$filters['keyword']}%");
        }

        // 2. Filter by Category
        if (!empty($filters['category'])) {
            // Giả sử URL là ?category=1
            $query->where('category_id', $filters['category']);
        }

        // 3. Filter by Price Range (nếu có)
        if (!empty($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
        }
        if (!empty($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }

        // 4. Sorting
        if (!empty($filters['sort'])) {
            switch ($filters['sort']) {
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
                    $query->orderBy('id', 'desc');
            }
        } else {
            $query->orderBy('id', 'desc');
        }

        return $query->paginate($perPage)->withQueryString();
    }

    public function getCategoriesWithProductCount()
    {
        // Lấy category và đếm số sản phẩm active
        // Yêu cầu Model Category phải có relation products()
        return Category::withCount('products')->get(); 
    }

    public function getProductDetails(int $id)
    {
        // Eager load category và reviews ngay lập tức để tránh N+1
        return $this->model->with(['category', 'reviews.user'])->findOrFail($id);
    }

    public function getRelatedProducts($currentProduct, int $limit = 4)
    {
        $minPrice = $currentProduct->price * 0.7;
        $maxPrice = $currentProduct->price * 1.3;

        // Logic tìm kiếm thông minh
        $related = $this->model->where('category_id', $currentProduct->category_id)
            ->where('id', '!=', $currentProduct->id)
            ->whereBetween('price', [$minPrice, $maxPrice])
            ->inRandomOrder()
            ->take($limit)
            ->get();

        // Fallback logic nếu không đủ sản phẩm
        if ($related->count() < $limit) {
            $more = $this->model->where('category_id', $currentProduct->category_id)
                ->where('id', '!=', $currentProduct->id)
                ->whereNotIn('id', $related->pluck('id'))
                ->take($limit - $related->count())
                ->get();
            
            $related = $related->merge($more);
        }

        return $related;
    }
}