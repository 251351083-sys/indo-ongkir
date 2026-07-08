<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    private function getProducts()
    {
        try {
            $dbProducts = DB::table('products')->get();
            $products = [];
            foreach ($dbProducts as $dbProd) {
                $products[] = [
                    'id'     => $dbProd->id,
                    'name'   => $dbProd->nama_varian ?? $dbProd->nama_produk ?? $dbProd->name ?? 'Premium Dubai Pistachio Cookies',
                    'harga'  => $dbProd->harga ?? 65000,
                    'stock'  => $dbProd->stok ?? $dbProd->stock ?? 20,
                    'weight' => $dbProd->berat ?? $dbProd->weight ?? 300,
                    'img'    => $dbProd->url_foto ?? $dbProd->img ?? 'https://noonchiinsight.com/wp-content/uploads/2026/01/download-1-1.jpg',
                ];
            }
            return !empty($products) ? $products : $this->defaultProducts();
        } catch (\Exception $e) {
            Log::error('Gagal ambil produk: ' . $e->getMessage());
            return $this->defaultProducts();
        }
    }

    private function defaultProducts()
    {
        return [[
            'id'     => 1,
            'name'   => 'Premium Dubai Pistachio Cookies',
            'harga'  => 65000,
            'stock'  => 20,
            'weight' => 300,
            'img'    => 'https://noonchiinsight.com/wp-content/uploads/2026/01/download-1-1.jpg',
        ]];
    }

    public function add(Request $request)
    {
        $products = $this->getProducts();
        $product  = collect($products)->firstWhere('id', $request->product_id);

        if (!$product) {
            return redirect()->back()->with('error', 'Produk tidak ditemukan.');
        }

        $cart = session()->get('cart', []);
        $id   = $request->product_id;

        if (isset($cart[$id])) {
            $cart[$id]['qty']++;
        } else {
            $cart[$id] = [
                'name'   => $product['name'],
                'price'  => $product['harga'],
                'weight' => $product['weight'],
                'img'    => $product['img'],
                'qty'    => 1,
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Produk ditambahkan ke keranjang.');
    }

    public function increase($id)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
            $cart[$id]['qty']++;
        }
        session()->put('cart', $cart);
        return redirect()->back();
    }

    public function decrease($id)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
            if ($cart[$id]['qty'] > 1) {
                $cart[$id]['qty']--;
            } else {
                unset($cart[$id]);
            }
        }
        session()->put('cart', $cart);
        return redirect()->back();
    }

    public function remove($id)
    {
        $cart = session()->get('cart', []);
        unset($cart[$id]);
        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Produk dihapus dari keranjang.');
    }

    public function clear()
    {
        session()->forget('cart');
        return redirect()->back()->with('success', 'Keranjang berhasil dikosongkan.');
    }
}