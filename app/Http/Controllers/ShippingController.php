<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Muma Bakery - IndoOngkir Toko UMKM</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            background-color: #FFF5F7; /* Latar Belakang Pink Super Soft */
            font-family: 'Poppins', sans-serif;
            color: #4A5568;
        }
        
        .header-bakery {
            background: linear-gradient(135deg, #FBC5C5 0%, #FFAEAE 100%); /* Pink Soft Gradient */
            color: white;
            padding: 30px 20px;
            border-bottom-left-radius: 25px;
            border-bottom-right-radius: 25px;
            box-shadow: 0 4px 15px rgba(251, 197, 197, 0.5);
        }

        .role-section {
            background-color: #ffffff;
            border-radius: 15px;
            padding: 15px;
            box-shadow: 0 4px 10px rgba(251, 197, 197, 0.2);
        }

        .btn-admin {
            background-color: #B9E0FF !important; /* Biru Soft */
            border: 1px solid #A3D2F7 !important;
            color: #2D3748 !important;
            font-weight: 600;
        }

        .btn-customer {
            background-color: #FBC5C5 !important; /* Pink Soft */
            border: 1px solid #EAA9A9 !important;
            color: white !important;
            font-weight: 600;
        }

        .section-title {
            color: #8A6E73;
            font-weight: 700;
            border-left: 5px solid #FBC5C5;
            padding-left: 12px;
            margin-bottom: 20px;
        }

        .card-produk {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            background-color: #ffffff;
            box-shadow: 0 6px 12px rgba(251, 197, 197, 0.25);
        }

        .card-produk:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 20px rgba(251, 197, 197, 0.4);
        }

        .btn-beli {
            background-color: #B9E0FF !important; /* Tombol Biru Soft */
            color: #2D3748 !important;
            border: none;
            font-weight: bold;
            border-radius: 10px;
            padding: 10px;
            transition: background 0.2s;
        }

        .btn-beli:hover {
            background-color: #A3D2F7 !important;
        }

        .form-control, .form-select {
            border-radius: 10px;
            border: 1px solid #FBC5C5;
        }

        .form-control:focus, .form-select:focus {
            border-color: #FFAEAE;
            box-shadow: 0 0 0 0.25rem rgba(251, 197, 197, 0.5);
        }
    </style>
</head>
<body>

    <div class="header-bakery text-center mb-4">
        <h1 class="fw-bold m-0">🧁 Muma Bakery Store 🧁</h1>
        <p class="m-0 mt-1 small opacity-92">IndoOngkir: Toko Online UMKM dengan Kalkulator Ongkir Real-Time</p>
    </div>

    <div class="container mb-5">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="text" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="role-section mb-4 text-center">
            <span class="fw-bold d-block mb-2 text-muted text-uppercase small">Status Hak Akses Saat Ini:</span>
            <h4 class="fw-bold text-dark mb-3">{{ session('user_role') == 'Admin' ? 'Admin / Owner' : 'Customer / Pembeli' }}</h4>
            <div class="btn-group" role="group">
                <a href="{{ url('/switch-role/customer') }}" class="btn btn-customer px-4 py-2">Masuk Customer</a>
                <a href="{{ url('/switch-role/admin') }}" class="btn btn-admin px-4 py-2">Masuk Admin Owner</a>
            </div>
        </div>

        <div class="row">
            @if(session('user_role') == 'Admin')
                <div class="col-lg-4 mb-4">
                    <div class="card p-4 shadow-sm border-0" style="border-radius: 20px;">
                        <h4 class="fw-bold mb-3 text-dark">Tambah Produk Baru</h4>
                        <form action="{{ url('/product/store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nama Menu/Varian</label>
                                <input type="text" name="name" class="form-control" placeholder="Contoh: Burnt Cheesecake" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Harga (Rp)</label>
                                <input type="number" name="harga" class="form-control" placeholder="45000" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Stok Kue</label>
                                <input type="number" name="stock" class="form-control" placeholder="15" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Berat Kue (Gram)</label>
                                <input type="number" name="weight" class="form-control" placeholder="500" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Link URL Foto Kue</label>
                                <input type="url" name="img" class="form-control" placeholder="https://images.unsplash.com/..." required>
                            </div>
                            <button type="submit" class="btn btn-customer w-100 py-2 fw-bold shadow-sm">Simpan Ke Etalase</button>
                        </form>
                    </div>
                </div>
            @endif

            <div class="{{ session('user_role') == 'Admin' ? 'col-lg-8' : 'col-12' }}">
                
                <h3 class="section-title">Etalase Live Aneka Kue Premium</h3>
                <div class="row">
                    @forelse($products as $p)
                        <div class="col-md-6 col-xl-4 mb-4">
                            <div class="card h-100 card-produk">
                                <img src="{{ $p['img'] ?? 'https://images.unsplash.com/photo-1533134242443-d4fd215305ad?w=500&q=80' }}" class="card-img-top" alt="Menu Muma Bakery" style="height: 180px; object-fit: cover;">
                                <div class="card-body p-3 d-flex flex-column justify-content-between">
                                    <div>
                                        <h5 class="fw-bold text-dark text-truncate mb-1">{{ $p['name'] ?? 'Premium Cake' }}</h5>
                                        <h6 class="text-success fw-bold mb-2">Rp {{ number_format($p['harga'] ?? 0, 0, ',', '.') }}</h6>
                                        <div class="d-flex justify-content-between text-muted small mb-3">
                                            <span>Stok: <b>{{ $p['stock'] ?? 0 }}</b> pcs</span>
                                            <span>Berat: <b>{{ $p['weight'] ?? 0 }} gr</b></span>
                                        </div>
                                    </div>
                                    <button class="btn btn-beli w-100 btn-hitung-ongkir" data-weight="{{ $p['weight'] ?? 0 }}" data-name="{{ $p['name'] ?? '' }}">
                                        Beli & Hitung Ongkir
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-warning text-center">Belum ada produk di etalase Muma Bakery.</div>
                        </div>
                    @endforelse
                </div>

                <div class="card p-4 border-0 shadow-sm mt-3" id="section-ongkir" style="border-radius: 20px; background-color: #ffffff;">
                    <h4 class="fw-bold mb-3 text-dark" style="border-left: 5px solid #B9E0FF; padding-left: 10px;">
                        🚚 Kalkulator Ongkir Real-Time (Starter Plan)
                    </h4>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Pilih Provinsi Tujuan</label>
                            <select id="provinsi" class="form-select">
                                <option value="">-- Pilih Provinsi --</option>
                                @foreach($provinces as $prov)
                                    <option value="{{ $prov['province_id'] }}">{{ $prov['province'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Pilih Kota/Kabupaten</label>
                            <select id="kota" class="form-select" disabled>
                                <option value="">-- Pilih Kota --</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Pilih Kurir Resmi Starter</label>
                            <select id="kurir" class="form-select">
                                <option value="jne">JNE (Jalur Nugraha Ekakurir)</option>
                                <option value="pos">POS Indonesia</option>
                                <option value="tiki">TIKI (Titipan Kilat)</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Total Berat Belanja (Gram)</label>
                            <input type="number" id="total-berat" class="form-control" value="0" readonly style="background-color: #edf2f7;">
                            <small class="text-muted" id="nama-kue-terpilih">Silakan klik tombol "Beli" pada produk di atas.</small>
                        </div>
                    </div>

                    <button type="button" id="btn-cek-ongkir" class="btn btn-customer w-100 py-2 fw-bold shadow-sm mt-2">
                        Hitung Ongkos Kirim Otomatis
                    </button>

                    <div id="hasil-ongkir" class="mt-4" style="display: none;">
                        <h5 class="fw-bold text-dark">Hasil Pencarian Tarif Kurir:</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped mt-2 align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Layanan (Service)</th>
                                        <th>Deskripsi</th>
                                        <th>Biaya Kirim</th>
                                        <th>Estimasi Tiba (Hari)</th>
                                    </tr>
                                </thead>
                                <tbody id="body-ongkir">
                                    </tbody>
                            </table>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            // Ketika tombol Beli di-klik, ambil data berat produk otomatis
            $('.btn-hitung-ongkir').click(function() {
                let berat = $(this).data('weight');
                let nama = $(this).data('name');
                $('#total-berat').val(berat);
                $('#nama-kue-terpilih').html('Kue terpilih: <b class="text-primary">' + nama + '</b> (' + berat + ' gram)');
                
                // Scroll halus ke area kalkulator ongkir
                $('html, body').animate({
                    scrollTop: $("#section-ongkir").offset().top - 20
                }, 500);
            });

            // AJAX: Ambil Dropdown Kota Berdasarkan Provinsi Pilihan
            $('#provinsi').change(function() {
                let provId = $(this).val();
                if(provId) {
                    $('#kota').html('<option value="">Sedang memuat data kota...</option>').prop('disabled', false);
                    $.get('/get-cities/' + provId, function(data) {
                        let html = '<option value="">-- Pilih Kota --</option>';
                        data.forEach(function(city) {
                            html += `<option value="${city.city_id}">${city.type} ${city.city_name}</option>`;
                        });
                        $('#kota').html(html);
                    });
                } else {
                    $('#kota').html('<option value="">-- Pilih Kota --</option>').prop('disabled', true);
                }
            });

            // AJAX: Hitung Biaya Ongkir Otomatis (Nembak API Starter JNE, POS, TIKI)
            $('#btn-cek-ongkir').click(function() {
                let kotaTujuan = $('#kota').val();
                let beratTotal = $('#total-berat').val();
                let kodeKurir  = $('#kurir').val();

                if(!kotaTujuan || beratTotal <= 0) {
                    alert('Silakan pilih produk kue dan tentukan kota tujuan pengiriman terlebih dahulu!');
                    return;
                }

                $('#body-ongkir').html('<tr><td colspan="4" class="text-center text-muted">Sedang menghitung tarif layanan...</td></tr>');
                $('#hasil-ongkir').show();

                $.post('/check-cost', {
                    _token: '{{ csrf_token() }}',
                    destination: kotaTujuan,
                    weight: beratTotal,
                    courier: kodeKurir
                }, function(data) {
                    let html = '';
                    if(data.length > 0 && data[0].costs.length > 0) {
                        let costs = data[0].costs;
                        costs.forEach(function(c) {
                            let formatHarga = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(c.cost[0].value);
                            html += `<tr>
                                <td class="fw-bold text-primary">${c.service}</td>
                                <td>${c.description}</td>
                                <td class="fw-bold text-success">${formatHarga}</td>
                                <td>${c.cost[0].etd ? c.cost[0].etd + ' Hari' : '-'}</td>
                            </tr>`;
                        });
                    } else {
                        html = '<tr><td colspan="4" class="text-center text-danger">Layanan kurir tidak tersedia untuk rute berat ini.</td></tr>';
                    }
                    $('#body-ongkir').html(html);
                }).fail(function() {
                    $('#body-ongkir').html('<tr><td colspan="4" class="text-center text-danger">Gagal terhubung ke API RajaOngkir. Periksa koneksi/API Key kelompok Anda.</td></tr>');
                });
            });
        });
    </script>
</body>
</html>