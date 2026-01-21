<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PaymentController;

// --- ADMIN CONTROLLERS ---
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;

require __DIR__.'/auth.php';

// ====================================================
// PUBLIC ROUTES (Khách vãng lai có thể truy cập)
// ====================================================

Route::get('/', [HomeController::class, 'index'])->name('home');

// [FIX QUAN TRỌNG] Đổi name thành 'shop.index' để khớp với Sidebar Filter
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/product/{id}', [ShopController::class, 'show'])->name('product.detail');

// Route nhận kết quả từ VNPay (Không cần đăng nhập cũng được, hoặc tùy logic)
Route::get('/payment/vnpay/callback', [PaymentController::class, 'vnpayCallback'])->name('payment.vnpay.callback');

// ====================================================
// CART ROUTES (Giỏ hàng)
// ====================================================
Route::group(['prefix' => 'cart', 'as' => 'cart.'], function () {
    Route::get('/', [CartController::class, 'index'])->name('index'); 
    Route::get('/add/{id}', [CartController::class, 'addToCart'])->name('add');
    Route::get('/remove/{id}', [CartController::class, 'removeFromCart'])->name('remove');
    Route::get('/update/{id}', [CartController::class, 'updateCart'])->name('update');
    Route::get('/clear', [CartController::class, 'clearCart'])->name('clear');
});

// ====================================================
// AUTHENTICATED ROUTES (Phải đăng nhập mới vào được)
// ====================================================
Route::middleware(['auth', 'verified'])->group(function () {
    
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // User Orders History
    Route::get('/my-orders', [App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
    Route::get('/my-orders/{id}', [App\Http\Controllers\OrderController::class, 'show'])->name('orders.show');

    // Checkout Flow (Bắt buộc đăng nhập mới được thanh toán)
    Route::group(['prefix' => 'checkout', 'as' => 'checkout.'], function () {
        Route::get('/', [CheckoutController::class, 'show'])->name('index');     // Trang điền thông tin
        Route::post('/process', [CheckoutController::class, 'process'])->name('process'); // Xử lý submit
        Route::get('/success', [CheckoutController::class, 'success'])->name('success'); // Trang thành công
    });
});

// ====================================================
// ADMIN ROUTES (Quyền Admin)
// ====================================================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Resource Controller (Tự động tạo index, create, store, show, edit, update, destroy)
    Route::resource('products', AdminProductController::class);
    Route::resource('categories', AdminCategoryController::class);

    // Custom Order Routes cho Admin
    Route::controller(AdminOrderController::class)->prefix('orders')->name('orders.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{id}', 'show')->name('show');
        Route::put('/{id}', 'update')->name('update');
    });
});