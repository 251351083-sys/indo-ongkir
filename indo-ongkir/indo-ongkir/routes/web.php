<?php
 
use App\Http\Controllers\ShippingController;
use Illuminate\Support\Facades\Route;
 
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/
 
// Halaman utama aplikasi (katalog, dasbor admin, biaya kirim, antrean)
Route::get('/shipping', [ShippingController::class, 'index'])->name('shipping.index');
 
// Ambil daftar kota berdasarkan id provinsi (dipanggil via fetch() dari JS)
Route::get('/get-cities/{province_id}', [ShippingController::class, 'getCities']);
 
// Hitung ongkos kirim
Route::post('/check-cost', [ShippingController::class, 'checkCost'])->name('check.cost');
 
// ---- CRUD Produk ----
Route::post('/product', [ShippingController::class, 'storeProduct'])->name('product.store');
Route::post('/product/{id}', [ShippingController::class, 'updateProduct'])->name('product.update');
Route::post('/product/{id}/delete', [ShippingController::class, 'deleteProduct'])->name('product.destroy');
 
// ---- Order / Antrean ----
Route::post('/order', [ShippingController::class, 'storeOrder'])->name('order.store');
 
// Arahkan root URL langsung ke halaman shipping (opsional, sesuaikan kalau kamu sudah punya halaman utama sendiri)
Route::get('/', function () {
    return redirect()->route('shipping.index');
});