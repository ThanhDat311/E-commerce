<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Thêm unique cho name nếu bạn muốn tên cũng không được trùng
            'name'           => ['required', 'string', 'max:255', 'unique:products,name'], 
            
            // Rule này đang hoạt động tốt
            'sku'            => ['required', 'string', 'max:50', 'unique:products,sku'],
            
            'price'          => ['required', 'numeric', 'min:0'],
            'sale_price'     => ['nullable', 'numeric', 'min:0', 'lt:price'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'category_id'    => ['required', 'exists:categories,id'],
            'description'    => ['nullable', 'string'],
            'image'          => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            // --- SỬA LẠI ĐOẠN NÀY CHO KHỚP VỚI RULES ---
            
            // Custom cho Name
            'name.required'      => 'Vui lòng nhập tên sản phẩm.',
            'name.unique'        => 'Tên sản phẩm này đã tồn tại, vui lòng chọn tên khác.',
            
            // Custom cho SKU (Đây là cái bạn đang cần)
            'sku.required'       => 'Mã SKU là bắt buộc.',
            'sku.unique'         => 'Mã SKU này đã tồn tại trong hệ thống. Vui lòng kiểm tra lại.',
            
            // Các field khác
            'price.required'     => 'Giá sản phẩm không được để trống.',
            'price.min'          => 'Giá sản phẩm không được âm.',
            'sale_price.lt'      => 'Giá khuyến mãi phải nhỏ hơn giá gốc.',
            'category_id.exists' => 'Danh mục đã chọn không hợp lệ.',
            'image.max'          => 'Kích thước ảnh quá lớn (Tối đa 2MB).',
            'image.image'        => 'File tải lên phải là hình ảnh.',

            'is_new'         => ['nullable', 'boolean'],
            'is_featured'    => ['nullable', 'boolean'],
        ];
    }
}