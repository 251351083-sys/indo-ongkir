<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class ShippingController extends Controller
{
    public function index()
    {
        $provinces = [];

        try {
            // Mengambil data wilayah Indonesia gratis tanpa API Key agar bebas error limit
            $backup = Http::withoutVerifying()->timeout(5)->get('https://emsifa.github.io/api-wilayah-indonesia/api/provinces.json');
            $provincesRaw = $backup->json() ?? [];
            foreach ($provincesRaw as $prov) {
                $provinces[] = [
                    'province_id' => $prov['id'],
                    'province' => $prov['name']
                ];
            }
        } catch (\Exception $e) {
            $provinces = [];
        }

        $totalWeight = 1000;
        try {
            $totalWeight = DB::table('carts')->sum('weight') ?: 1000;
        } catch (\Exception $e) {
            $totalWeight = 1000; 
        }

        return view('shiping', compact('provinces', 'totalWeight'));
    }

    public function getCities($province_id)
    {
        $cities = [];

        try {
            $backup = Http::withoutVerifying()->timeout(5)->get("https://emsifa.github.io/api-wilayah-indonesia/api/regencies/{$province_id}.json");
            $citiesRaw = $backup->json() ?? [];
            foreach ($citiesRaw as $city) {
                $cities[] = [
                    'city_id' => $city['id'],
                    'type' => '',
                    'city_name' => $city['name']
                ];
            }
        } catch (\Exception $e) {
            $cities = [];
        }

        return response()->json($cities);
    }

    public function checkCost(Request $request)
    {
        $beratKg = $request->weight / 1000;
        $tarif = $request->courier == 'pos' ? 12000 : ($request->courier == 'tiki' ? 14000 : 15000);
        $total = max($tarif, $tarif * $beratKg);
        
        $results = [
            [
                'service' => 'REG',
                'description' => 'Layanan Regular ' . strtoupper($request->courier),
                'cost' => [['value' => $total, 'etd' => '2-3']]
            ],
            [
                'service' => 'ECO',
                'description' => 'Layanan Ekonomis ' . strtoupper($request->courier),
                'cost' => [['value' => max($total - 4000, 9000), 'etd' => '4-5']]
            ]
        ];

        return back()->with([
            'results' => $results,
            'weight'  => $request->weight
        ]);
    }
}