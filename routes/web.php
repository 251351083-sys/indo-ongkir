<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;

// Rute Tampilan Utama (Membaca API RajaOngkir & Keranjang)
Route::get('/', [CartController::class, 'index'])->name('shipping.index');
Route::get('/get-cities/{province_id}', [CartController::class, 'getCities']);

// Modul Fitur Keranjang Belanja Berdasarkan Revisi
Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
Route::get('/cart/clear', [CartController::class, 'clearCart'])->name('cart.clear');
Route::get('/cart/delete/{id}', [CartController::class, 'removeFromCart']);
Route::get('/cart/increase/{id}', [CartController::class, 'increaseQty']);
Route::get('/cart/decrease/{id}', [CartController::class, 'decreaseQty']);
Route::get('/switch-role/{role}', [CartController::class, 'switchRole']);
// Modul CRUD Manajemen Produk
Route::post('/product/store', [CartController::class, 'storeProduct'])->name('product.store');
Route::get('/product/delete/{id}', [CartController::class, 'deleteProduct']);

// Modul Hitung Cost & Checkout
Route::post('/checkout', [CartController::class, 'checkout'])->name('checkout');
