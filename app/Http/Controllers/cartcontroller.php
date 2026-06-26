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
                    'key'    => 'M2PIOplPdfed907618ac602fenhpQHlp', 
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
            \Log::error("Gagal mengambil provinsi: " . $e->getMessage());
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
                'key' => 'M2PIOplPdfed907618ac602fenhpQHlp'
            ])->get("https://api.rajaongkir.com/starter/city?province={$province_id}");

            $cities = $response->json()['rajaongkir']['results'] ?? [];
            
            $formattedCities = array_map(function($city) {
                return [
                    'city_id'   => $city['city_id'] ?? '',
                    'city_name' => ($city['type'] ?? '') . ' ' . ($city['city_name'] ?? ''),
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
                'key' => 'M2PIOplPdfed907618ac602fenhpQHlp'
            ])->post('https://api.rajaongkir.com/starter/cost', [
                'origin'      => 411, // Purwakarta
                'destination' => $request->destination,
                'weight'      => $request->weight ?? 300,
                'courier'     => strtolower($request->courier ?? 'jne')
            ]);

            $costs = $response->json()['rajaongkir']['results'] ?? [];
            return response()->json($costs);
        } catch (\Exception $e) {
            return response()->json([]);
        }
    }
}