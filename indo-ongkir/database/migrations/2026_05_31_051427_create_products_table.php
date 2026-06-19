<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');              // Nama Produk
            $table->string('image')->nullable();  // Foto Produk (boleh kosong dulu)
            $table->integer('price');            // Harga Produk
            $table->integer('stock');            // Stok Produk
            $table->integer('weight');           // Berat gram (Penting buat RajaOngkir nanti!)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
