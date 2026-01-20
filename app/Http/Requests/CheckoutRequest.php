<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Cho phép tất cả user (kể cả guest nếu hệ thống cho phép)
        // Nếu chỉ cho user đăng nhập mua hàng, bạn có thể check: return auth()->check();
        return true; 
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name'  => ['required', 'string', 'max:255'],
            
            // Email bắt buộc và phải đúng định dạng
            'email'      => ['required', 'email', 'max:255'],
            
            // Validate số điện thoại (đơn giản). 
            // Nếu muốn chặt chẽ hơn cho VN: 'regex:/^(0)[0-9]{9}$/'
            'phone'      => ['required', 'string', 'min:10', 'max:15'],
            
            'address'    => ['required', 'string', 'max:500'],
            'note'       => ['nullable', 'string', 'max:1000'],
            
            // Validate phương thức thanh toán (chỉ chấp nhận các giá trị định trước)
            'payment_method' => ['required', 'string', 'in:cod,vnpay,momo'], 
        ];
    }

    /**
     * Custom messages (Optional - tốt cho UX Tiếng Việt)
     */
    public function messages(): array
    {
        return [
            'first_name.required' => 'Vui lòng nhập tên của bạn.',
            'email.required'      => 'Email là bắt buộc để nhận thông tin đơn hàng.',
            'phone.regex'         => 'Số điện thoại không đúng định dạng.',
            'payment_method.in'   => 'Phương thức thanh toán không hợp lệ.',
        ];
    }
}