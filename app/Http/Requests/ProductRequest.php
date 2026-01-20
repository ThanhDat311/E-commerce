<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Cho phép mọi user đã đăng nhập sử dụng
    }

    public function rules()
    {
        // Lấy ID sản phẩm nếu đang ở trang Edit (để check trùng SKU)
        $productId = $this->route('product') ? $this->route('product')->id : null;

        return [
            'name'           => 'required|string|max:255',
            // SKU là duy nhất, nhưng khi Edit thì bỏ qua ID hiện tại
            'sku'            => ['required', Rule::unique('products')->ignore($productId)],
            'category_id'    => 'required|exists:categories,id',
            'price'          => 'required|numeric|min:0',
            'sale_price'     => 'nullable|numeric|min:0|lt:price', // Giá sale phải nhỏ hơn giá gốc
            'stock_quantity' => 'required|integer|min:0',
            // Ảnh: bắt buộc khi Tạo mới, không bắt buộc khi Edit
            'image'          => ($this->isMethod('post') ? 'required' : 'nullable') . '|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description'    => 'nullable|string',
        ];
    }
}