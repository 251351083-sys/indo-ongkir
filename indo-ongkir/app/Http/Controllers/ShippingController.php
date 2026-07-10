<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
 
class ShippingController extends Controller
{
    /**
     * Deteksi otomatis nama kolom stok di tabel products (bisa 'stock' atau 'stok'
     * tergantung migration mana yang beneran dipakai bikin tabelnya).
     */
    private function kolomStok()
    {
        if (Schema::hasColumn('products', 'stock')) return 'stock';
        if (Schema::hasColumn('products', 'stok')) return 'stok';
        return 'stock';
    }
 
    public function index()
    {
        $provinces = [];
        $products  = [];
        
        // Ambil produk dari DB
        try {
            $dbProducts = DB::table('products')->get();
            foreach ($dbProducts as $dbProd) {
                $products[] = [
                    'id'          => $dbProd->id,
                    'name'        => $dbProd->nama_varian ?? $dbProd->nama_produk ?? $dbProd->name ?? 'Premium Dubai Pistachio Cookies',
                    'harga'       => $dbProd->harga ?? 65000,
                    'stock'       => $dbProd->stok ?? $dbProd->stock ?? 20,
                    'weight'      => $dbProd->berat ?? $dbProd->weight ?? 300,
                    'description' => $dbProd->description ?? '',
                    'img'         => $dbProd->url_foto ?? $dbProd->img ?? 'https://i.pinimg.com/736x/01/a0/62/01a0625906f30e61d8825227d894b9ce.jpg',
                ];
            }
        } catch (\Exception $e) {
            Log::error('Gagal ambil produk: ' . $e->getMessage());
        }
 
        // 🌟 6 PRODUK DEMO INI SELALU TAMPIL, DIGABUNG DENGAN PRODUK ASLI DARI DATABASE 🌟
        // Catatan: id dikasih prefix "demo-" biar nggak pernah bentrok dengan id produk
        // asli dari database (yang berupa angka murni seperti 1, 2, 3, ...).
        $produkDemo = [
            [
                'id'     => 'demo-1',
                'name'   => 'Premium Dubai Pistachio Cookies',
                'harga'  => 65000,
                'stock'  => 20,
                'weight' => 300,
                'img'    => 'https://i.pinimg.com/736x/f9/00/f4/f900f4fbb3f5a0f442d7c1cd6cd7036f.jpg',
            ],
            [
                'id'     => 'demo-2',
                'name'   => 'Choco Lava Soft Cookie',
                'harga'  => 45000,
                'stock'  => 15,
                'weight' => 250,
                'img'    => 'https://i.pinimg.com/736x/ae/17/63/ae1763d5d5e2331f3cf143b48f951d34.jpg',
            ],
            [
                'id'     => 'demo-3',
                'name'   => 'Matcha Crunch Croissant',
                'harga'  => 38000,
                'stock'  => 25,
                'weight' => 200,
                'img'    => 'https://i.pinimg.com/736x/46/71/e6/4671e6f8dc684323f7079880e3ab1cf9.jpg',
            ],
            [
                'id'     => 'demo-4',
                'name'   => 'Almond Butter Pastry',
                'harga'  => 42000,
                'stock'  => 12,
                'weight' => 180,
                'img'    => 'https://i.pinimg.com/736x/87/86/c2/8786c259b28b1baf4eb23388a8632fdd.jpg',
            ],
            [
                'id'     => 'demo-5',
                'name'   => 'Red Velvet Cheese Cake',
                'harga'  => 55000,
                'stock'  => 10,
                'weight' => 350,
                'img'    => 'https://i.pinimg.com/1200x/53/1d/ed/531ded52ee8ff810c4cdb3a864396c0a.jpg',
            ],
            [
                'id'     => 'demo-6',
                'name'   => 'Cinnamon Bun',
                'harga'  => 48000,
                'stock'  => 10,
                'weight' => 300,
                'img'    => 'https://i.pinimg.com/1200x/4f/72/4c/4f724c7c6cf4ffec87c4f680a3118308.jpg',
            ],
        ];
 
        // Produk demo taruh di depan, produk asli dari database menyusul di belakang.
        $products = array_merge($produkDemo, $products);
 
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
     *
     * PERBAIKAN: nama kolom database yang sebenarnya adalah
     * nama_produk, berat, url_foto (bukan name, weight, img).
     * Lihat: php artisan tinker -> Schema::getColumnListing('products')
     */
    public function storeProduct(Request $request)
    {
        try {
            $imgPath = 'https://i.pinimg.com/736x/01/a0/62/01a0625906f30e61d8825227d894b9ce.jpg';
 
            if ($request->hasFile('foto')) {
                $path = $request->file('foto')->store('products', 'public');
                $imgPath = asset('storage/' . $path);
            }
 
            $kolomStok = $this->kolomStok();
 
            $id = DB::table('products')->insertGetId([
                'nama_produk' => $request->name,
                'harga'       => $request->harga,
                'berat'       => $request->weight,
                $kolomStok    => $request->stock,
                'description' => $request->description,
                'url_foto'    => $imgPath,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
 
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'product' => [
                        'id'          => $id,
                        'name'        => $request->name,
                        'harga'       => (int) $request->harga,
                        'weight'      => (int) $request->weight,
                        'stock'       => (int) $request->stock,
                        'description' => $request->description,
                        'img'         => $imgPath,
                    ],
                ]);
            }
 
            return redirect()->back()->with('success', 'Produk Berhasil Ditambahkan!');
        } catch (\Exception $e) {
            Log::error('Gagal menambah produk: ' . $e->getMessage());
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'Gagal menambah produk: ' . $e->getMessage());
        }
    }
 
    /**
     * Update harga, stok, dan deskripsi produk yang sudah ada
     */
    public function updateProduct(Request $request, $id)
    {
        try {
            $kolomStok = $this->kolomStok();
 
            DB::table('products')->where('id', $id)->update([
                'harga'       => $request->harga,
                $kolomStok    => $request->stock,
                'description' => $request->description,
                'updated_at'  => now(),
            ]);
 
            if ($request->wantsJson()) {
                return response()->json(['success' => true]);
            }
 
            return redirect()->back()->with('success', 'Produk berhasil diperbarui!');
        } catch (\Exception $e) {
            Log::error('Gagal update produk id=' . $id . ': ' . $e->getMessage());
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'Gagal update produk: ' . $e->getMessage());
        }
    }
 
    /**
     * Menghapus produk dari tabel 'products' berdasarkan id.
     * Catatan: produk demo (id berupa "demo-1", "demo-2", dst, bukan angka)
     * tidak ada di database, jadi langsung dianggap berhasil dihapus dari
     * sisi tampilan saja tanpa perlu query DB.
     */
    public function deleteProduct(Request $request, $id)
    {
        try {
            // Produk demo (id non-numerik) memang tidak tersimpan di DB,
            // jadi cukup anggap sukses biar JS di frontend bisa hapus dari tampilan.
            if (!is_numeric($id)) {
                if ($request->wantsJson()) {
                    return response()->json(['success' => true]);
                }
                return redirect()->back()->with('success', 'Produk demo dihapus dari tampilan.');
            }
 
            DB::table('products')->where('id', $id)->delete();
 
            if ($request->wantsJson()) {
                return response()->json(['success' => true]);
            }
 
            return redirect()->back()->with('success', 'Produk berhasil dihapus!');
        } catch (\Exception $e) {
            Log::error('Gagal hapus produk id=' . $id . ': ' . $e->getMessage());
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'Gagal menghapus produk: ' . $e->getMessage());
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
 