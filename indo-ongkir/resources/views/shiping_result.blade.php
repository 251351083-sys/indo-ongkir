<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Hasil Perhitungan Ongkos Kirim') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="p-6 bg-white shadow sm:rounded-lg space-y-6">
                
                <div class="border-b pb-4">
                    <h3 class="text-lg font-medium text-gray-900">Rincian Paket</h3>
                    <p class="text-sm text-gray-600 mt-1">Produk: <strong>{{ $product->name }}</strong></p>
                    <p class="text-sm text-gray-600">Total Berat: <strong>{{ number_format($totalWeight) }} gram</strong></p>
                </div>

                <h3 class="text-md font-semibold text-gray-800">Pilihan Layanan Tarif Resmi:</h3>
                
                <div class="space-y-3">
                    @forelse($costs as $cost)
                        <div class="p-4 border rounded-lg flex justify-between items-center bg-gray-50 hover:bg-gray-100">
                            <div>
                                <p class="font-bold text-indigo-700 uppercase">{{ $cost['service'] }}</p>
                                <p class="text-xs text-gray-500">Estimasi sampai: {{ $cost['cost'][0]['etd'] }} Hari</p>
                            </div>
                            <div class="text-right">
                                <p class="font-extrabold text-lg text-gray-900">Rp {{ number_format($cost['cost'][0]['value']) }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-red-500 text-center">Layanan pengiriman tidak tersedia untuk rute berat ini.</p>
                    @endforelse
                </div>

                <div class="pt-4">
                    <a href="{{ route('shipping.index') }}" class="inline-block text-sm text-indigo-600 hover:underline">
                        &larr; Hitung Ulang Ongkir Lain
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>