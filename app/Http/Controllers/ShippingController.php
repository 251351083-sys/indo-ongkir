<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ShippingController extends Controller
{
    public function index()
    {
        $provinces = [];
        $products  = [];

        // Ambil produk dari DB
        try {
            $dbProducts = DB::table('products')->get();
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
        } catch (\Exception $e) {
            Log::error('Gagal ambil produk: ' . $e->getMessage());
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

        // Ambil provinsi dari Komerce
        try {
            $response = Http::withoutVerifying()
                ->timeout(10)
                ->withHeaders(['key' => env('KOMERCE_KEY')])
                ->get(env('KOMERCE_BASE_URL') . '/province');

            $apiData = $response->json()['data'] ?? [];

            foreach ($apiData as $prov) {
                $provinces[] = [
                    'province_id'   => $prov['id'] ?? '',
                    'province_name' => $prov['name'] ?? '',
                ];
            }
        } catch (\Exception $e) {
            Log::error('Gagal ambil provinsi: ' . $e->getMessage());
        }

        $cart        = session()->get('cart', []);
        $totalPrice  = 0;
        $totalWeight = 0;

        foreach ($cart as $item) {
            $totalPrice  += ($item['price'] ?? 0) * ($item['qty'] ?? 1);
            $totalWeight += ($item['weight'] ?? 0) * ($item['qty'] ?? 1);
        }

        return view('shiping', compact('provinces', 'products', 'cart', 'totalPrice', 'totalWeight'));
    }

    public function getCities($province_id)
    {
        try {
            $response = Http::withoutVerifying()
                ->withHeaders(['key' => env('KOMERCE_KEY')])
                ->get(env('KOMERCE_BASE_URL') . "/city?province={$province_id}");

            $cities = array_map(function ($city) {
                return [
                    'city_id'   => $city['id'] ?? '',
                    'city_name' => $city['name'] ?? '',
                    'type'      => $city['type'] ?? '',
                ];
            }, $response->json()['data'] ?? []);

            return response()->json($cities);
        } catch (\Exception $e) {
            return response()->json([]);
        }
    }

    public function checkCost(Request $request)
    {
        try {
            $response = Http::withoutVerifying()
                ->withHeaders(['key' => env('KOMERCE_KEY')])
                ->post(env('KOMERCE_BASE_URL') . '/cost', [
                    'origin'      => env('ORIGIN_CITY_ID', 411),
                    'destination' => $request->destination,
                    'weight'      => $request->weight ?? 300,
                    'courier'     => strtolower($request->courier ?? 'jne'),
                ]);

            return response()->json($response->json()['data'] ?? []);
        } catch (\Exception $e) {
            return response()->json([]);
        }
    }
}