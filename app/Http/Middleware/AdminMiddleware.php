<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Kiểm tra đã đăng nhập chưa?
        if (!Auth::check()) {
            // Chưa đăng nhập -> Đá về trang login
            return redirect()->route('login')->with('error', 'Please login to access Admin area.');
        }

        // 2. Kiểm tra có phải là Admin không?
        if (Auth::user()->role !== 'admin') {
            // Đăng nhập rồi nhưng là khách hàng -> Báo lỗi 403 (Forbidden)
            abort(403, 'Unauthorized access. You are not an Admin.');
        }

        // 3. Nếu thỏa mãn cả 2 -> Cho đi tiếp
        return $next($request);
    }
}