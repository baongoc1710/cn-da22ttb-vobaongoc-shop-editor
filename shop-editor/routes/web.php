<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DesignController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
// Page
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/design', [HomeController::class, 'designer'])->name('design');

// API cho Ajax (Designer)
Route::post('/api/upload-image', [DesignController::class, 'uploadImage']);
Route::post('/api/save-design', [DesignController::class, 'saveDesign']);

Route::get('/my-collection', [DesignController::class, 'myCollection'])->name('collection.index');
Route::delete('/my-collection/{id}', [DesignController::class, 'destroy'])->name('collection.destroy');

// Cart & Order
Route::post('/cart/add', [OrderController::class, 'addToCart'])->name('cart.add');
Route::get('/cart', function () {
    return view('cart.cart');
})->name('cart.index');
Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
Route::post('/checkout', [OrderController::class, 'placeOrder'])->name('checkout.store');
Route::patch('/cart/update', [OrderController::class, 'updateCart'])->name('cart.update');
Route::delete('/cart/remove', [OrderController::class, 'removeCart'])->name('cart.remove');
// Route danh sách đơn hàng
Route::get('/my-orders', [OrderController::class, 'index'])->name('orders.index');
// Route xem chi tiết 1 đơn hàng
Route::get('/my-orders/{id}', [OrderController::class, 'show'])->name('orders.show');
// Route Hủy đơn hàng
Route::post('/my-orders/{id}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');


// AUTH ROUTES
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Group route yêu cầu đăng nhập
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
});

// Group Route cho Admin (Nên có middleware kiểm tra quyền admin)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('products', AdminProductController::class);
    // === CÁC ROUTE MỚI CHO PRODUCT COLORS ===
    // 1. Thêm màu mới
    Route::post('products/{id}/colors', [AdminProductController::class, 'storeColor'])->name('products.colors.store');
    // 2. Cập nhật màu
    Route::put('products/colors/{color_id}', [AdminProductController::class, 'updateColor'])->name('products.colors.update');
    // 3. Xóa màu
    Route::delete('products/colors/{color_id}', [AdminProductController::class, 'destroyColor'])->name('products.colors.destroy');
    // Route xóa màu (destroyColor
    Route::get('orders/print-labels', [AdminOrderController::class, 'printLabels'])->name('orders.print');

    // Sau đó mới đến route resource chung (index, show, update...)
    Route::resource('orders', AdminOrderController::class)->only(['index', 'show', 'update']);
});
