<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CartController extends Controller
{
    public function index()
    {
        $apiKey = env('KOMERCE_API_KEY');
        $provinces = [];

        // 1. WAJIB DIKURINGI TRY-CATCH AGAR TIDAK EROR LAYAR MERAH
        try {
            $response = Http::withoutVerifying()
                ->timeout(10) // Batasi waktu tunggu maksimal 10 detik
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Accept'        => 'application/json',
                ])->get('https://api.komerce.id/site/v1/rajaongkir/province');
            
            if ($response->successful()) {
                $apiData = $response->json()['data'] ?? [];
                foreach ($apiData as $prov) {
                    $provinces[] = [
                        'province_id'   => $prov['id'] ?? '', 
                        'province_name' => $prov['name'] ?? ''
                    ];
                }
            }
        } catch (\Exception $e) {
            // Kalau server Komerce rontok/gagal resolve, amankan variabel agar blade tidak crash
            $provinces = [];
        }

        // 2. Oper data provinsi ke view blade kamu
        return view('shiping', compact('provinces'));
    }
}