<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\ProductController; // Tambahkan controller ini jika ada
use App\Http\Controllers\OrderController;   // Tambahkan controller ini jika ada

// Rute Dashboard bawaan Laravel Breeze (Biar gak eror saat login/register)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::get('/', function () {
        return redirect()->route('shipping.index');
    });

    Route::get('/shipping', [ShippingController::class, 'index'])->name('shipping.index');

    Route::get('/get-cities/{province_id}', [ShippingController::class, 'getCities'])->name('get.cities');
    Route::post('/check-cost', [ShippingController::class, 'checkCost'])->name('check.cost');

    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
    Route::get('/cart/increase/{id}', [CartController::class, 'increase'])->name('cart.increase');
    Route::get('/cart/decrease/{id}', [CartController::class, 'decrease'])->name('cart.decrease');
    Route::get('/cart/delete/{id}', [CartController::class, 'remove'])->name('cart.delete');

    Route::get('/switch-role/{role}', function ($role) {
        session(['user_role' => $role]);
        return redirect()->back();
    })->name('switch.role');

    // ======================================================================
    // 🌟 SELEKSI RUTE BARU BIAR TOMBOL PUBLISH & PESANAN ADMIN BERFUNGSI 🌟
    // ======================================================================
    
    // Rute untuk memproses Form Tambah Produk dari Admin
    Route::post('/product/store', [ShippingController::class, 'storeProduct'])->name('product.store');
    
    // Rute untuk memproses Form Kirim Pesanan Nyata dari Pelanggan ke Database Admin
    Route::post('/order/store', [ShippingController::class, 'storeOrder'])->name('order.store');

});

// PENTING: Pastikan baris ini ada di paling bawah luar group middleware
require __DIR__.'/auth.php';