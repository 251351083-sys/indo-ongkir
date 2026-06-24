<?php

// Pastikan di bagian atas file route sudah ada import ini:
use App\Http\Controllers\CartController;

Route::middleware('auth')->group(function () {
    
    // 1. UBAH SEMUA SHIPPINGCONTROLLER MENJADI CARTCONTROLLER
    Route::get('/shipping', [CartController::class, 'index'])->name('shipping.index');
    Route::get('/get-cities/{province_id}', [CartController::class, 'getCities']);
    Route::post('/check-cost', [CartController::class, 'checkCost']);

    // Rute bawaan lainnya...
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});