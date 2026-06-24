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

        // 1. DATA PRODUK
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
 
        try {
    $response = Http::withoutVerifying()
        ->timeout(10)
        ->withHeaders([
            
            'key' => 'OuclSMU9dd39c571326fee77LLRBmBTe', 
            'Accept' => 'application/json',
        ])->get('https://api.rajaongkir.com/starter/province'); 

    $resJson = $response->json();
    $apiData = $resJson['rajaongkir']['results'] ?? [];
    
    foreach ($apiData as $prov) {
        $provinces[] = [
            'province_id'   => $prov['province_id'] ?? '',
            'province_name' => $prov['province'] ?? '', 
        ];
    }
} catch (\Exception $e) {
    $provinces = [];
}

        $cart = session()->get('cart', []);
        $totalPrice = 0;
        $totalWeight = 0;

        foreach ($cart as $item) {
            $totalPrice += ($item['price'] ?? 0) * ($item['qty'] ?? 1);
            $totalWeight += ($item['weight'] ?? 0) * ($item['qty'] ?? 1);
        }

        return view('shiping', compact('provinces', 'products', 'cart', 'totalPrice', 'totalWeight'));
    }

    public function getCities($province_id)
    {
        try {
            
            $response = Http::withoutVerifying()->withHeaders([
            'key' => 'OuclSMU9dd39c571326fee77LLRBmBTe'
            ])->get("https://api.rajaongkir.com/starter/city?province={$province_id}");

            $cities = $response->json()['rajaongkir']['results'] ?? [];
            
            $formattedCities = array_map(function($city) {
                return [
                    'city_id'   => $city['id'] ?? '',
                    'city_name' => $city['name'] ?? '',
                    'type'      => $city['type'] ?? ''
                ];
            }, $cities);

            return response()->json($formattedCities);
        } catch (\Exception $e) {
            return response()->json([]);
        }
    }

    public function checkCost(Request $request)
    {
        try {
            $response = Http::withoutVerifying()->withHeaders([
                'key' => 'OuclSMU9dd39c571326fee77LLRBmBTe'
            ])->post('https://api.komerce.id/site/v1/rajaongkir/cost', [
                'origin'        => 411, 
                'destination'   => $request->destination,
                'weight'        => $request->weight ?? 300,
                'courier'       => $request->courier ?? 'jne'
            ]);

            return response()->json($response->json()['data'] ?? []);
        } catch (\Exception $e) {
            return response()->json([]);
        }
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
            
        }

        return redirect()->back()->with('success', 'Data cookies berhasil disimpan dan siap dijual!');
    }

    public function switchRole($role, Request $request)
    {
        session(['user_role' => $role]);
        return redirect()->to('/');
    }
    
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
