<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class CartController extends Controller
{
    public function index()
    {
        $provinces = [];
        $products = [];

        // 1. DATA PRODUk 
        try {
            $dbProducts = DB::table('products')->get();
            foreach ($dbProducts as $dbProd) {
                $products[] = [
                    'id'     => $dbProd->id ?? 1,
                    'name'   => $dbProd->nama_varian ?? $dbProd->nama_produk ?? $dbProd->name ?? 'Premium Dubai Pistachio Cookies',
                    'harga'  => $dbProd->harga ?? 65000,
                    'stock'  => $dbProd->stok ?? $dbProd->stock ?? 20,
                    'weight' => $dbProd->berat ?? $dbProd->weight ?? 300,
                    'img'    => $dbProd->url_foto ?? $dbProd->img ?? 'https://noonchiinsight.com/wp-content/uploads/2026/01/download-1-1.jpg',
                ];
            }
        } catch (\Exception $e) {
            $products = [];
        }

        if (empty($products)) {
            $products[] = [
                'id'     => 1,
                'name'   => 'Premium Dubai Pistachio Cookies',
                'harga'  => 65000,
                'stock'  => 20,
                'weight' => 300,
                'img'    => 'https://noonchiinsight.com/wp-content/uploads/2026/01/download-1-1.jpg',
            ];
        }

        // 2. KONEKSI RAJAONGKIR (KOMERCE)
        try {
            $response = Http::withoutVerifying()
                ->timeout(10)
                ->withHeaders([
                    'Authorization' => 'Bearer OuclSMU9dd39c571326fee77LLRBmBTe',
                    'Accept'        => 'application/json',
                ])->get('https://api.komerce.id/site/v1/rajaongkir/province');
            
            if ($response->successful() && isset($response->json()['data'])) {
                $apiData = $response->json()['data'];
                foreach ($apiData as $prov) {
                    $provinces[] = [
                        'province_id' => $prov['id'] ?? '',
                        'province_name' => $prov['name'] ?? '', // Disamakan 'province_name' sesuai kebutuhan file blade
                    ];
                }
            }
        } catch (\Exception $e) {
            $provinces = [];
        }

        // Ambil data keranjang, total harga, dan total berat dari session untuk dikirim ke view
        $cart = session()->get('cart', []);
        $totalPrice = 0;
        $totalWeight = 0;

        foreach ($cart as $item) {
            $totalPrice += ($item['price'] ?? 0) * ($item['qty'] ?? 1);
            $totalWeight += ($item['weight'] ?? 0) * ($item['qty'] ?? 1);
        }

        return view('shiping', compact('provinces', 'products', 'cart', 'totalPrice', 'totalWeight'));
    }

    public function storeProduct(Request $request)
    {
        try {
            DB::table('products')->insertOrIgnore([
                'nama_varian' => $request->input('nama_varian') ?? $request->input('nama_produk') ?? $request->input('name') ?? 'Premium Dubai Cookies',
                'harga'       => $request->input('harga') ?? 65000,
                'stok'        => $request->input('stok') ?? $request->input('stok_jar') ?? $request->input('stock') ?? 20,
                'berat'       => $request->input('berat') ?? $request->input('weight') ?? 300,
                'url_foto'    => $request->input('url_foto') ?? $request->input('link_url_foto_cookies') ?? $request->input('img') ?? '',
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        } catch (\Exception $e) {
            // Bypass aman
        }

        return redirect()->back()->with('success', 'Data cookies berhasil disimpan dan siap d jual!');
    }

    public function switchRole($role, Request $request)
    {
        session(['user_role' => $role]);
        return redirect()->to('/');
    }

    // ==========================================
    // TAMBAHKAN FUNGSI BARU DI BAWAH INI
    // ==========================================
    
    public function addToCart(Request $request)
    {
        $cart = session()->get('cart', []);
        $id = $request->id;

        if (isset($cart[$id])) {
            $cart[$id]['qty']++;
        } else {
            $cart[$id] = [
                "id"     => $request->id,
                "name"   => $request->name,
                "qty"    => 1,
                "price"  => $request->price ?? $request->harga ?? 0,
                "weight" => $request->weight ?? 0
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Cookies berhasil dimasukkan ke keranjang!');
    }

    public function clear()
    {
        session()->forget('cart');
        return redirect()->back()->with('success', 'Keranjang belanja berhasil dikosongkan.');
    }
}
