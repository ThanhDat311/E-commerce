<?php

namespace App\Repositories\Eloquent;

use App\Models\Product;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository implements ProductRepositoryInterface
{
    // 2. Khai báo biến $model
    protected $model;

    // 3. Khởi tạo (Inject) Model vào Repository
    public function __construct(Product $model)
    {
        $this->model = $model;
    }

    /**
     * Tìm 1 sản phẩm theo ID
     */
    public function find($id)
    {
        return $this->model->find($id);
    }

    /**
     * Tìm tất cả sản phẩm
     */
    public function all()
    {
        return $this->model->all();
    }

    /**
     * (Hàm mới fix lỗi trước đó)
     * Tìm nhiều sản phẩm theo danh sách ID
     */
    public function findByIds(array $ids)
    {
        return $this->model->whereIn('id', $ids)->get();
    }

    // --- BẮT BUỘC PHẢI CÓ HÀM NÀY ---
    public function create(array $data)
    {
        return Product::create($data);
    }

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
        if ($product) {
            return $product->delete();
        }
        return false;
    }
}
