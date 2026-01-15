<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- NHÓM 1: TRANG CHỦ & THÔNG TIN (HomeController) ---
Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'index')->name('home');
    Route::get('/contact', 'contact')->name('contact');
});

// --- NHÓM 2: CỬA HÀNG & SẢN PHẨM (ShopController) ---
Route::controller(ShopController::class)->group(function () {
    Route::get('/shop', 'index')->name('shop');
    Route::get('/product/{id}', 'show')->name('product.detail');
});

// --- NHÓM 3: GIỎ HÀNG (CartController) - Cấu trúc Cha/Con ---
// Tất cả các route trong này sẽ có:
// 1. URL bắt đầu bằng: /cart/... (trừ khi định nghĩa lại)
// 2. Tên Route bắt đầu bằng: cart....
Route::controller(CartController::class)
    ->prefix('cart')           // Tiền tố URL (Cha)
    ->name('cart.')            // Tiền tố Name (Cha)
    ->group(function () {      // Các route Con

        // URL: /cart
        // Name: cart.index
        Route::get('/', 'index')->name('index');

        // URL: /cart/add/{id}  <-- Đã đổi từ /add-to-cart thành /cart/add cho đúng chuẩn
        // Name: cart.add
        Route::get('/add/{id}', 'addToCart')->name('add');

        // URL: /cart/remove/{id}
        // Name: cart.remove
        Route::get('/remove/{id}', 'remove')->name('remove');

        // URL: /cart/update/{id}/{quantity}
        // Name: cart.update
        Route::get('/update/{id}/{quantity}', 'update')->name('update');

        // URL: /cart/checkout  <-- Gom vào nhóm cart luôn
        // Name: cart.checkout
        Route::get('/checkout', 'checkout')->name('checkout');

        // URL: /cart/place-order (Phương thức POST để gửi form)
        Route::post('/place-order', 'placeOrder')->name('placeOrder');

        // URL: /cart/order-success (Trang cảm ơn)
        Route::get('/order-success', 'orderSuccess')->name('orderSuccess');
    });