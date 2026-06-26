<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ShippingController extends Controller
{
    
    public function index()
    {
        return redirect()->route('shipping.index'); 
    }

    public function getCities($province_id)
    {
        try {
            $response = Http::withoutVerifying()->withHeaders([
                'key' => 'OuclSMU9dd39c571326fee77LLRBmBTe'
            ])->get("https://api.komerce.id/site/v1/rajaongkir/city?province={$province_id}");

            $cities = $response->json()['data'] ?? [];
            
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
}