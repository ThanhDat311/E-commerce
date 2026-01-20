<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest; 
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    // 1. Danh sách sản phẩm
    public function index()
    {
        $products = Product::with('category')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    // 2. Form thêm mới
    public function create()
    {
        $categories = Category::all(); 
        return view('admin.products.create', compact('categories'));
    }

    // 3. Xử lý lưu (Store)
    public function store(ProductRequest $request)
    {
        $data = $request->validated();

        // Xử lý Upload Ảnh
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            
            // Đảm bảo thư mục tồn tại
            if (!File::exists(public_path('img/products'))) {
                File::makeDirectory(public_path('img/products'), 0755, true);
            }

            $file->move(public_path('img/products'), $filename);
            $data['image_url'] = 'img/products/' . $filename;
        }

        // Checkbox logic
        $data['is_new'] = $request->has('is_new') ? 1 : 0;
        $data['is_featured'] = $request->has('is_featured') ? 1 : 0;

        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    // 4. Form sửa (Edit)
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    // 5. Xử lý cập nhật (Update)
    public function update(ProductRequest $request, Product $product)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            // Xóa ảnh cũ
            if ($product->image_url && File::exists(public_path($product->image_url))) {
                File::delete(public_path($product->image_url));
            }

            // Lưu ảnh mới
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('img/products'), $filename);
            $data['image_url'] = 'img/products/' . $filename;
        }

        $data['is_new'] = $request->has('is_new') ? 1 : 0;
        $data['is_featured'] = $request->has('is_featured') ? 1 : 0;

        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    // 6. Xóa (Delete)
    public function destroy(Product $product)
    {
        if ($product->image_url && File::exists(public_path($product->image_url))) {
            File::delete(public_path($product->image_url));
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }
}