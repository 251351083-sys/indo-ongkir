<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ShippingController;

// Load route auth dari Breeze (login, register, dll)
require __DIR__.'/auth.php';

// Rute Dashboard bawaan Laravel Breeze (Biar proses login/register aman)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    // Redirect root ke halaman utama
    Route::get('/', function () {
        return redirect()->route('shipping.index');
    });

    // Halaman utama
    Route::get('/shipping', [ShippingController::class, 'index'])->name('shipping.index');

    // AJAX Shipping
    Route::get('/get-cities/{province_id}', [ShippingController::class, 'getCities'])->name('get.cities');
    Route::post('/check-cost', [ShippingController::class, 'checkCost'])->name('check.cost');

    // 🟢 RUTE PRODUK (Untuk Handle Form Input & Edit Produk khusus Owner)
    Route::post('/products', [ShippingController::class, 'storeProduct'])->name('product.store');
    Route::post('/products/{id}', [ShippingController::class, 'updateProduct'])->name('product.update');
    Route::post('/products/{id}/delete', [ShippingController::class, 'deleteProduct'])->name('product.destroy');

    // Cart
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
    Route::get('/cart/increase/{id}', [CartController::class, 'increase'])->name('cart.increase');
    Route::get('/cart/decrease/{id}', [CartController::class, 'decrease'])->name('cart.decrease');
    Route::get('/cart/delete/{id}', [CartController::class, 'remove'])->name('cart.delete');

    // Switch Role
    Route::get('/switch-role/{role}', function ($role) {
        session(['user_role' => $role]);
        return redirect()->back();
    })->name('switch.role');

});