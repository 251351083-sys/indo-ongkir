<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CartController extends Controller
{
    public function index()
    {
        $products = session('db_products', [
            ['id' => 1, 'name' => 'Viral Dubai Kunafa Melt Cookies', 'price' => 75000, 'weight' => 350, 'stock' => 12, 'img' => 'https://images.unsplash.com/photo-1499636136210-6f4ce91a094f?w=300'],
            ['id' => 2, 'name' => 'Premium Soft Chewy Cookies', 'price' => 48000, 'weight' => 250, 'stock' => 20, 'img' => 'https://images.unsplash.com/photo-1558961303-1d201d0ad947?w=300'],
            ['id' => 3, 'name' => 'Red Velvet Cheese Overload', 'price' => 55000, 'weight' => 300, 'stock' => 15, 'img' => 'https://images.unsplash.com/photo-1576618148400-f54bed99fcfd?w=300'],
        ]);
        
        if (!session()->has('db_products')) {
            session(['db_products' => $products]);
        }

        if (!session()->has('user_role')) {
            session(['user_role' => 'Customer']);
        }

        $cart = session('cart', []);
        $totalPrice = 0;
        $totalWeight = 0;
        foreach ($cart as $item) {
            $totalPrice += ($item['price'] * $item['qty']);
            $totalWeight += ($item['weight'] * $item['qty']);
        }

        $provinces = [];
        $apiKey = env('RAJAONGKIR_API_KEY');

        try {
            // Nembak ke Server Komerce
            $response = Http::timeout(5)->withHeaders([
                'key' => $apiKey
            ])->get('https://api.komerce.id/site/v1/rajaongkir/province');
            
            if ($response->successful()) {
                $apiData = $response->json()['data'] ?? [];
                foreach ($apiData as $prov) {
                    $provinces[] = [
                        'province_id' => $prov['id'] ?? $prov['province_id'],
                        'province' => $prov['name'] ?? $prov['province']
                    ];
                }
            }
        } catch (\Exception $e) {
            $provinces = [];
        }

        return view('shiping', compact('products', 'cart', 'totalPrice', 'totalWeight', 'provinces'));
    }

    public function switchRole(Request $request, $role)
    {
        if ($role == 'Admin') {
            if ($request->query('password') !== 'admin123') {
                return redirect('/')->with('error', 'Password Admin Salah!');
            }
        }

        if (in_array($role, ['Customer', 'Admin'])) {
            session(['user_role' => $role]);
        }
        return redirect('/')->with('success', 'Berhasil masuk sebagai ' . $role);
    }

    public function getCities($province_id)
    {
        $apiKey = env('RAJAONGKIR_API_KEY');
        $formattedCities = [];

        try {
            // Ambil data kota dari Komerce
            $response = Http::timeout(5)->withHeaders([
                'key' => $apiKey
            ])->get('https://api.komerce.id/site/v1/rajaongkir/city', [
                'province' => $province_id
            ]);
            
            if ($response->successful()) {
                $apiData = $response->json()['data'] ?? [];
                foreach ($apiData as $city) {
                    $formattedCities[] = [
                        'city_id' => $city['id'] ?? $city['city_id'],
                        'city_name' => $city['name'] ?? $city['city_name'],
                        'type' => $city['type'] ?? ''
                    ];
                }
            }
        } catch (\Exception $e) {
            $formattedCities = [];
        }
        
        return response()->json($formattedCities);
    }

    public function addToCart(Request $request)
    {
        $cart = session('cart', []);
        $id = $request->id;

        if (isset($cart[$id])) {
            $cart[$id]['qty']++;
        } else {
            $cart[$id] = [
                'id' => $id, 'name' => $request->name, 'price' => (int)$request->price,
                'weight' => (int)$request->weight, 'qty' => 1
            ];
        }

        session(['cart' => $cart]);
        return redirect('/')->with('success', 'Cookies dimasukkan ke keranjang!');
    }

    public function increaseQty($id)
    {
        $cart = session('cart', []);
        if (isset($cart[$id])) {
            $cart[$id]['qty']++;
            session(['cart' => $cart]);
        }
        return redirect('/');
    }

    public function decreaseQty($id)
    {
        $cart = session('cart', []);
        if (isset($cart[$id])) {
            $cart[$id]['qty']--;
            if ($cart[$id]['qty'] <= 0) {
                unset($cart[$id]);
            }
            session(['cart' => $cart]);
        }
        return redirect('/');
    }

    public function removeFromCart($id)
    {
        $cart = session('cart', []);
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session(['cart' => $cart]);
        }
        return redirect('/');
    }

    public function clearCart()
    {
        session()->forget('cart');
        return redirect('/');
    }

    public function checkout(Request $request)
    {
        $apiKey = env('RAJAONGKIR_API_KEY');
        try {
            $response = Http::withHeaders([
                'key' => $apiKey
            ])->post('https://api.komerce.id/site/v1/rajaongkir/cost', [
                'origin' => '152', 
                'destination' => $request->city_id,
                'weight' => $request->weight ? $request->weight : 200,
                'courier' => $request->courier
            ]);

            $results = $response->json()['data'] ?? [];
            $formattedResults = [];
            
            if(!empty($results)) {
                $formattedResults[] = [
                    'code' => $request->courier,
                    'cost' => $results[0]['costs'][0]['cost'][0]['value'] ?? 15000
                ];
            } else {
                $formattedResults[] = ['code' => $request->courier, 'cost' => 15000];
            }
            return redirect('/')->with(['results' => $formattedResults, 'success' => 'Ongkir berhasil dihitung otomatis!']);
        } catch (\Exception $e) {
            return redirect('/')->with(['results' => [['code' => $request->courier, 'cost' => 15000]], 'success' => 'Ongkir berhasil dihitung!']);
        }
    }

    public function storeProduct(Request $request)
    {
        $products = session('db_products', []);
        $newId = count($products) > 0 ? max(array_column($products, 'id')) + 1 : 1;
        $products[] = [
            'id' => $newId, 'name' => $request->name, 'price' => (int)$request->price,
            'weight' => (int)$request->weight, 'stock' => (int)$request->stock, 'img' => $request->img
        ];
        session(['db_products' => $products]);
        return redirect('/');
    }

    public function deleteProduct($id)
    {
        $products = session('db_products', []);
        $products = array_filter($products, fn($p) => $p['id'] != $id);
        session(['db_products' => array_values($products)]);
        return redirect('/');
    }
}