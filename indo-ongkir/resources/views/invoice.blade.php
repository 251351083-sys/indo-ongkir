<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice Pembayaran UMKM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print { .no-print { display: none; } }
    </style>
</head>
<body class="bg-white p-5" onload="window.print()">
    <div class="container border p-4 rounded" style="max-width: 500px; margin-top: 30px;">
        <div class="text-center mb-4">
            <h4 class="fw-bold mb-0">INVOICE TOKO INDOONGKIR</h4>
            <small class="text-muted">Status Transaksi: <span class="badge bg-warning text-dark">Belum Bayar</span></small>
        </div>
        <hr>
        <h6 class="fw-bold text-secondary mb-3">Rincian Pembelian:</h6>
        
        @if(isset($order['items']) && count($order['items']) > 0)
            <table class="table table-sm table-borderless">
                @foreach($order['items'] as $item)
                <tr>
                    <td>{{ $item['name'] }} (x{{ $item['qty'] }})</td>
                    <td class="text-end">Rp {{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}</td>
                </tr>
                @endforeach
                <tr class="border-top">
                    <td class="pt-2">Subtotal Barang:</td>
                    <td class="text-end pt-2">Rp {{ number_format($order['total'] - $order['ongkir'], 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Ongkos Kirim ({{ $order['courier'] }}):</td>
                    <td class="text-end">Rp {{ number_format($order['ongkir'], 0, ',', '.') }}</td>
                </tr>
                <tr class="table-light fw-bold fs-5 border-top">
                    <td class="py-2">TOTAL BAYAR:</td>
                    <td class="text-end text-success py-2">Rp {{ number_format($order['total'], 0, ',', '.') }}</td>
                </tr>
            </table>
        @else
            <p class="text-muted text-center small">Belum ada rincian belanja.</p>
        @endif

        <div class="text-center mt-4 no-print">
            <button onclick="window.print()" class="btn btn-primary btn-sm me-2">🖨️ Cetak Nota</button>
            <a href="{{ url('/') }}" class="btn btn-secondary btn-sm">Kembali ke Toko</a>
        </div>
    </div>
</body>
</html>