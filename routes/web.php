<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/product/{id}', [ShopController::class, 'show'])->name('product.detail');

Route::group(['prefix' => 'cart', 'as' => 'cart.'], function () {
    Route::get('/', [CartController::class, 'index'])->name('index'); // Tên đầy đủ là cart.index
    Route::get('/add/{id}', [CartController::class, 'addToCart'])->name('add'); // Tên đầy đủ là cart.add
});

Route::get('/shop', [ShopController::class, 'index'])->name('shop');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    // Danh sách đơn hàng
    Route::get('/my-orders', [OrderController::class, 'index'])->name('orders.index');
    
    // Chi tiết đơn hàng
    Route::get('/my-orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    
});

require __DIR__.'/auth.php';
