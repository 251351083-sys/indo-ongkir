<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // Daftarkan kolom yang boleh diisi
    protected $fillable = ['name', 'image', 'price', 'stock', 'weight'];
}