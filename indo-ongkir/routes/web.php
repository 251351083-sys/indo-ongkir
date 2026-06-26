<?php

use App\Http\Controllers\ShippingController;

Route::middleware('auth')->group(function () {
    
    // Halaman utama shipping
    Route::get('/shipping', [ShippingController::class, 'index'])->name('shipping.index');

    // Dua rute AJAX ini WAJIB ada sesuai dengan script di blade kamu:
    Route::get('/get-cities/{province_id}', [ShippingController::class, 'getCities']);
    Route::post('/check-cost', [ShippingController::class, 'checkCost']);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});