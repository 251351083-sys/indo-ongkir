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
        $products = [];

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

        $provinces = [
            ['province_id' => '1',  'province_name' => 'Bali'],
            ['province_id' => '2',  'province_name' => 'Bangka Belitung'],
            ['province_id' => '3',  'province_name' => 'Banten'],
            ['province_id' => '4',  'province_name' => 'Bengkulu'],
            ['province_id' => '5',  'province_name' => 'DI Yogyakarta'],
            ['province_id' => '6',  'province_name' => 'DKI Jakarta'],
            ['province_id' => '7',  'province_name' => 'Gorontalo'],
            ['province_id' => '8',  'province_name' => 'Jambi'],
            ['province_id' => '9',  'province_name' => 'Jawa Barat'],
            ['province_id' => '10', 'province_name' => 'Jawa Tengah'],
            ['province_id' => '11', 'province_name' => 'Jawa Timur'],
            ['province_id' => '12', 'province_name' => 'Kalimantan Barat'],
            ['province_id' => '13', 'province_name' => 'Kalimantan Selatan'],
            ['province_id' => '14', 'province_name' => 'Kalimantan Tengah'],
            ['province_id' => '15', 'province_name' => 'Kalimantan Timur'],
            ['province_id' => '16', 'province_name' => 'Kalimantan Utara'],
            ['province_id' => '17', 'province_name' => 'Kepulauan Riau'],
            ['province_id' => '18', 'province_name' => 'Lampung'],
            ['province_id' => '19', 'province_name' => 'Maluku'],
            ['province_id' => '20', 'province_name' => 'Maluku Utara'],
            ['province_id' => '21', 'province_name' => 'Nusa Tenggara Barat'],
            ['province_id' => '22', 'province_name' => 'Nusa Tenggara Timur'],
            ['province_id' => '23', 'province_name' => 'Papua'],
            ['province_id' => '24', 'province_name' => 'Papua Barat'],
            ['province_id' => '25', 'province_name' => 'Riau'],
            ['province_id' => '26', 'province_name' => 'Sulawesi Barat'],
            ['province_id' => '27', 'province_name' => 'Sulawesi Selatan'],
            ['province_id' => '28', 'province_name' => 'Sulawesi Tengah'],
            ['province_id' => '29', 'province_name' => 'Sulawesi Tenggara'],
            ['province_id' => '30', 'province_name' => 'Sulawesi Utara'],
            ['province_id' => '31', 'province_name' => 'Sumatera Barat'],
            ['province_id' => '32', 'province_name' => 'Sumatera Selatan'],
            ['province_id' => '33', 'province_name' => 'Sumatera Utara'],
            ['province_id' => '34', 'province_name' => 'Aceh'],
        ];

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
                ->get(env('KOMERCE_BASE_URL') . "/destination/city?province_id={$province_id}");

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
                ->post(env('KOMERCE_BASE_URL') . '/calculate/domestic-cost', [
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