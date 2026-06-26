<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [CartController::class, 'index'])->name('home');

Route::get('/shipping', [CartController::class, 'index'])->name('shipping.index');
Route::get('/get-cities/{province_id}', [CartController::class, 'getCities']);
Route::post('/check-cost', [CartController::class, 'checkCost']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});