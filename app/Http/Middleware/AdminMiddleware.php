<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Kiểm tra xem người dùng đã đăng nhập chưa
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // 2. Kiểm tra quyền Admin dựa trên hàm isAdmin() trong Model
        if ($user->isAdmin()) {
            return $next($request); // Cho phép đi tiếp
        }

        // 3. Nếu không phải Admin, trả về lỗi 403 hoặc redirect
        // Bạn có thể return view('errors.403') nếu có file view tùy chỉnh
        abort(403, 'Unauthorized access. You are not an Admin.');
    }
}