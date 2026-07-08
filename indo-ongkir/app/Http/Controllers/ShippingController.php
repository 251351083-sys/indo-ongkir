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
                    'img'    => $dbProd->url_foto ?? $dbProd->img ?? 'https://i.pinimg.com/736x/01/a0/62/01a0625906f30e61d8825227d894b9ce.jpg',
                ];
            }
        } catch (\Exception $e) {
            Log::error('Gagal ambil produk: ' . $e->getMessage());
        }

        // 🌟 JIKA DATABASE KOSONG, 5 PRODUK INI AKAN OTOMATIS MUNCUL DI HALAMAN PELANGGAN 🌟
        if (empty($products)) {
            // Produk 1
            $products[] = [
                'id'     => 1,
                'name'   => 'Premium Dubai Pistachio Cookies',
                'harga'  => 65000,
                'stock'  => 20,
                'weight' => 300,
                'img'    => 'https://i.pinimg.com/736x/f9/00/f4/f900f4fbb3f5a0f442d7c1cd6cd7036f.jpg',
            ];

            // Produk 2
            $products[] = [
                'id'     => 2,
                'name'   => 'Choco Lava Soft Cookie',
                'harga'  => 45000,
                'stock'  => 15,
                'weight' => 250,
                'img'    => 'https://i.pinimg.com/736x/ae/17/63/ae1763d5d5e2331f3cf143b48f951d34.jpg',
            ];

            // Produk 3
            $products[] = [
                'id'     => 3,
                'name'   => 'Matcha Crunch Croissant',
                'harga'  => 38000,
                'stock'  => 25,
                'weight' => 200,
                'img'    => 'https://i.pinimg.com/736x/46/71/e6/4671e6f8dc684323f7079880e3ab1cf9.jpg',
            ];

            // Produk 4
            $products[] = [
                'id'     => 4,
                'name'   => 'Almond Butter Pastry',
                'harga'  => 42000,
                'stock'  => 12,
                'weight' => 180,
                'img'    => 'https://i.pinimg.com/736x/87/86/c2/8786c259b28b1baf4eb23388a8632fdd.jpg',
            ];

            // Produk 5
            $products[] = [
                'id'     => 5,
                'name'   => 'Red Velvet Cheese Cake',
                'harga'  => 55000,
                'stock'  => 10,
                'weight' => 350,
                'img'    => 'https://i.pinimg.com/1200x/53/1d/ed/531ded52ee8ff810c4cdb3a864396c0a.jpg',
            ];

             $products[] = [
                'id'     => 6,
                'name'   => 'Cinnamon Bun',
                'harga'  => 48000,
                'stock'  => 10,
                'weight' => 300,
                'img'    => 'https://i.pinimg.com/1200x/4f/72/4c/4f724c7c6cf4ffec87c4f680a3118308.jpg',
            ];
        }

        // Ambil provinsi dari Binderbyte
        try {
            $response = Http::withoutVerifying()
                ->timeout(10)
                ->get('http://api.binderbyte.com/wilayah/provinsi', [
                    'api_key' => env('BINDERBYTE_API_KEY'),
                ]);
            $apiData = $response->json()['value'] ?? [];
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

        // Ambil data antrean pesanan dari database untuk Admin
        $orders = [];
        try {
            $orders = DB::table('orders')->orderBy('id', 'desc')->get()->map(function($order) {
                return [
                    'order_id'      => $order->order_id ?? 'MUMA-'.rand(1000,9999),
                    'customer_name' => $order->customer_name ?? 'Pelanggan',
                    'total_weight'  => $order->total_weight ?? 300,
                    'city_name'     => $order->city_name ?? 'Tidak Diketahui',
                ];
            });
        } catch (\Exception $e) {
            Log::error('Gagal ambil data orders: ' . $e->getMessage());
        }

        return view('shiping', compact('provinces', 'products', 'cart', 'totalPrice', 'totalWeight', 'orders'));
    }

    public function getCities($province_id)
    {
        try {
            $response = Http::withoutVerifying()
                ->get('http://api.binderbyte.com/wilayah/kabupaten', [
                    'api_key'     => env('BINDERBYTE_API_KEY'),
                    'id_provinsi' => $province_id,
                ]);
            $cities = array_map(function ($city) {
                return [
                    'city_id'   => $city['id'] ?? '',
                    'city_name' => $city['name'] ?? '',
                    'type'      => '',
                ];
            }, $response->json()['value'] ?? []);
            return response()->json($cities);
        } catch (\Exception $e) {
            return response()->json([]);
        }
    }

    public function checkCost(Request $request)
    {
        $weight = $request->weight;
        if (is_array($weight)) {
            $weight = reset($weight);
        }
        $weight = (int) ($weight ?? 300);
        $courier = strtolower($request->courier ?? 'jne');

        $tarifDasar = 15000;
        $tarifPerKg = 9000;
        $beratKg    = ceil($weight / 1000);
        $ongkir     = $tarifDasar + ($beratKg * $tarifPerKg);

        return response()->json([
            [
                'code'  => $courier,
                'name'  => strtoupper($courier),
                'costs' => [
                    [
                        'service'     => 'REG',
                        'description' => 'Layanan Reguler',
                        'cost'        => [
                            [
                                'value' => $ongkir,
                                'etd'   => '2-3',
                                'note'  => ''
                            ]
                        ]
                    ]
                ]
            ]
        ]);
    }

    /**
     * Menyimpan data produk baru ke tabel 'products'
     */
    public function storeProduct(Request $request)
    {
        try {
            DB::table('products')->insert([
                'name'       => $request->name,
                'harga'      => $request->harga,
                'weight'     => $request->weight,
                'stock'      => $request->stock,
                'img'        => 'https://i.pinimg.com/736x/01/a0/62/01a0625906f30e61d8825227d894b9ce.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect()->back()->with('success', 'Produk Berhasil Ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambah produk: ' . $e->getMessage());
        }
    }

    /**
     * Menyimpan data antrean order baru ke tabel 'orders'
     */
    public function storeOrder(Request $request)
    {
        try {
            DB::table('orders')->insert([
                'order_id'      => 'MUMA-' . rand(10000, 99990),
                'customer_name' => $request->customer_name ?? 'Pelanggan Anonim',
                'total_weight'  => $request->total_weight ?? 500,
                'city_name'     => $request->city_name ?? 'Kota Tujuan',
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);

            return redirect()->back()->with('success', 'Orderan Berhasil Masuk Antrean!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membuat antrean order: ' . $e->getMessage());
        }
    }
}