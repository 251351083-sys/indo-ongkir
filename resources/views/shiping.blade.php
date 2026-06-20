<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Indo-Ongkir</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { 
            background-color: #f7f9fa; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding-bottom: 90px;
        }
        .navbar-top { background: linear-gradient(135deg, #8B4513, #D2691E); } /* Tema Cokelat Cookies */
        .card-custom { border: none; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.04); background-color: #ffffff; }
        .product-img { height: 160px; object-fit: cover; border-radius: 8px; }
        
        /* NAV BAR POJOK KANAN ATAS (ONLY ICON) */
        .cart-header-icon-btn {
            position: relative;
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.4);
            width: 42px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            color: #ffffff;
            font-size: 20px;
            text-decoration: none;
            transition: all 0.2s;
        }
        .cart-header-icon-btn:hover { background: rgba(255, 255, 255, 0.3); color: #fff; }
        .cart-badge-circle {
            position: absolute; top: -4px; right: -4px;
            background-color: #92b8fb; color: #fff;
            font-size: 10px; font-weight: bold; border-radius: 50%;
            width: 18px; height: 18px; display: flex;
            align-items: center; justify-content: center;
            border: 1px solid #8B4513;
        }

        /* STATUS ORDER TAB BERJEJER */
        .shopee-tabs { display: flex; border-bottom: 2px solid #e8e8e8; margin-bottom: 20px; background: #fff; border-radius: 8px; overflow: hidden; }
        .shopee-tab-item { flex: 1; text-align: center; padding: 12px 5px; font-size: 14px; color: #555; cursor: pointer; font-weight: 500; border-bottom: 3px solid transparent; }
        .shopee-tab-item.active { color: #8B4513; border-bottom-color: #8B4513; font-weight: bold; }

        /* DESIGN BOTTOM NAVIGATION BAR */
        .bottom-nav {
            position: fixed; bottom: 0; left: 0; right: 0; height: 65px;
            background-color: #ffffff; box-shadow: 0 -3px 12px rgba(0, 0, 0, 0.06);
            display: flex; justify-content: space-around; align-items: center; z-index: 9999;
            border-top: 1px solid #eee;
        }
        .bottom-nav-item {
            text-decoration: none; display: flex; flex-direction: column;
            align-items: center; justify-content: center; color: #757575;
            font-size: 11px; font-weight: 500; width: 33.33%; height: 100%;
            background: none; border: none; cursor: pointer; transition: all 0.2s;
        }
        .bottom-nav-item.active { color: #8B4513; font-weight: bold; }
        .bottom-nav-icon { font-size: 20px; margin-bottom: 3px; }

        .custom-tab-content { display: none; }
        .custom-tab-content.active { display: block; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm mb-4 navbar-top">
        <div class="container d-flex justify-content-between align-items-center">
            <a class="navbar-brand fw-bold d-flex align-items-center" href="#">
                 <span class="ms-2 fs-5">Indo-Ongkir</span>
            </a>
            
            <a href="#" class="cart-header-icon-btn" id="btn-keranjang-atas" title="Lihat Keranjang">
                🛒
                @if(isset($cart) && count($cart) > 0)
                    <span class="cart-badge-circle">{{ count($cart) }}</span>
                @endif
            </a>
        </div>
    </nav>

    <div class="container">
        <div class="card card-custom p-3 mb-4 border shadow-sm">
            <div class="row align-items-center">
                <div class="col-md-7 mb-2 mb-md-0">
                    <span class="text-secondary d-block small">Status Hak Akses Akun Saat Ini:</span>
                    <strong class="h5">
                        {{ session('user_role') == 'Admin' ? ' Admin Owner Toko' : ' Customer / Pembeli' }}
                    </strong>
                </div>
                <div class="col-md-5 text-md-end">
                    <div class="btn-group" role="group">
                        <a href="{{ url('/switch-role/Customer') }}" class="btn btn-sm {{ session('user_role') == 'Customer' ? 'btn-primary active fw-bold' : 'btn-outline-primary' }}">
                            Masuk Customer
                        </a>
                        <button onclick="mintaPasswordAdmin()" class="btn btn-sm {{ session('user_role') == 'Admin' ? 'btn-danger active fw-bold' : 'btn-outline-danger' }}">
                            Masuk Admin Owner
                        </button>
                    </div>
                </div>
            </div>
        </div>

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-3 small shadow-sm" role="alert">
                ⚠️ {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 small shadow-sm" role="alert">
                  {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="custom-tab-content active" id="konten-beranda">
            
            @if(session('user_role') == 'Admin')
                <div class="card card-custom p-4 mb-4 border border-warning" style="background-color: #fffdf9;">
                    <div class="d-flex align-items-center mb-3">
                        <span class="fs-4 me-2">🛠️</span>
                        <h5 class="fw-bold text-dark mb-0">Input CRUD Manajemen Produk Cookies (Khusus Admin)</h5>
                    </div>
                    <form action="{{ route('product.store') }}" method="POST" class="row g-3 align-items-end"> @csrf
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold text-muted mb-1">Nama Varian Cookies</label>
                            <input type="text" class="form-control form-control-sm" name="name" placeholder="Misal: Premium Dubai Pistachio Cookies" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-semibold text-muted mb-1">Harga (Rp)</label>
                            <input type="number" class="form-control form-control-sm" name="price" placeholder="65000" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-semibold text-muted mb-1">Stok Jar</label>
                            <input type="number" class="form-control form-control-sm" name="stock" placeholder="50" min="1" value="20" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-semibold text-muted mb-1">Berat (Gram)</label>
                            <input type="number" class="form-control form-control-sm" name="weight" placeholder="300" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold text-muted mb-1">Link URL Foto Cookies</label>
                            <input type="url" class="form-control form-control-sm" name="img" placeholder="https://...">
                        </div>
                        <div class="col-12 text-end mt-3">
                            <button type="submit" class="btn btn-sm btn-dark px-4 fw-bold">+ Simpan Ke Database</button>
                        </div>
                    </form>
                </div>
            @endif

            <div class="card card-custom p-4">
                <h5 class="fw-bold text-dark mb-3">Etalase Live Aneka Cookies Premium</h5>
                <div class="row">
                    @if(isset($products) && count($products) > 0)
                        @foreach($products as $p)
                        <div class="col-6 col-md-3 mb-3">
                            <div class="card h-100 border p-2 position-relative shadow-none">
                                
                                @if(session('user_role') == 'Admin')
                                    <a href="{{ url('/product/delete/'.$p['id']) }}" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2 rounded-circle shadow-sm" style="padding: 2px 8px; font-size: 11px;" title="Hapus Produk dari Toko" onclick="return confirm('Yakin ingin menghapus produk ini?')">✕</a>
                                @endif

                                <img src="{{ $p['img'] ?? 'https://images.unsplash.com/photo-1499636136210-6f4ce91a094f?w=300' }}" class="card-img-top product-img">
                                <div class="card-body p-2 d-flex flex-column justify-content-between">
                                    <div>
                                        <div class="fw-bold text-dark text-truncate mb-1" style="font-size:14px;">{{ $p['name'] }}</div>
                                        <div class="text-success fw-bold small mb-1">Rp {{ number_format($p['price'], 0, ',', '.') }}</div>
                                        <div class="d-flex justify-content-between text-muted" style="font-size: 11px;">
                                            <span>Stok: <b>{{ $p['stock'] ?? 10 }}</b></span>
                                            <span>{{ $p['weight'] }} gr</span>
                                        </div>
                                    </div>
                                    <form action="{{ route('cart.add') }}" method="POST" class="mt-2"> @csrf
                                        <input type="hidden" name="id" value="{{ $p['id'] }}">
                                        <input type="hidden" name="name" value="{{ $p['name'] }}">
                                        <input type="hidden" name="price" value="{{ $p['price'] }}">
                                        <input type="hidden" name="weight" value="{{ $p['weight'] }}">
                                        <button class="btn btn-outline-warning text-dark w-100 btn-sm fw-bold" style="font-size: 11px; background-color: #fff8ee;">+ Keranjang</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        <div class="custom-tab-content" id="konten-pengiriman">
            <div class="row">
                <div class="col-lg-5 mb-4">
                    <div class="card card-custom p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold mb-0">🛒 Keranjang Belanja</h5>
                            <a href="{{ route('cart.clear') }}" class="btn btn-sm btn-link text-decoration-none text-danger p-0 small">Reset</a>
                        </div>
                        
                        @if(isset($cart) && count($cart) > 0)
                            <div class="p-2 border rounded bg-light mb-3" style="max-height: 280px; overflow-y:auto;">
                                @foreach($cart as $key => $c)
                                    @php 
                                        $idItem = (isset($c['id']) && $c['id'] != '') ? $c['id'] : $key; 
                                    @endphp
                                    <div class="d-flex justify-content-between align-items-center small mb-2 border-bottom pb-2">
                                        <div class="text-truncate" style="max-width: 150px;">
                                            <div class="fw-bold text-dark text-truncate">{{ $c['name'] ?? 'Cookies Premium' }}</div>
                                            <small class="text-muted">Rp {{ number_format($c['price'] ?? 0, 0, ',', '.') }}</small>
                                        </div>
                                        
                                        <div class="d-flex align-items-center">
                                            <div class="btn-group btn-group-sm me-2" role="group">
                                                <a href="{{ url('/cart/decrease/'.$idItem) }}" class="btn btn-outline-secondary px-2 py-0 fw-bold" style="font-size:10px;">➖</a>
                                                <span class="btn btn-light px-2 py-0 disabled fw-bold text-dark" style="font-size:12px;">{{ $c['qty'] ?? 1 }}</span>
                                                <a href="{{ url('/cart/increase/'.$idItem) }}" class="btn btn-outline-secondary px-2 py-0 fw-bold" style="font-size:10px;">➕</a>
                                            </div>
                                            
                                            <span class="fw-bold text-dark me-2 small">Rp {{ number_format(($c['price'] ?? 0) * ($c['qty'] ?? 1), 0, ',', '.') }}</span>
                                            
                                            <a href="{{ url('/cart/delete/'.$idItem) }}" class="btn btn-sm btn-link text-danger p-0" title="Hapus total produk ini" style="font-size: 15px; text-decoration: none;">
                                                🗑️
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="d-flex justify-content-between small mb-1">
                                <span class="text-muted">Total Berat Adonan:</span>
                                <span class="fw-bold text-dark">{{ $totalWeight ?? 0 }} Gram</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Subtotal Cookies:</span>
                                <span class="fw-bold text-danger fs-5">Rp {{ number_format($totalPrice ?? 0, 0, ',', '.') }}</span>
                            </div>
                        @else
                            <div class="text-center py-5 text-muted small">
                                Keranjang kue kosong. Pilih varian cookies lezat di tab Beranda.
                            </div>
                        @endif
                    </div>
                </div>

                <div class="col-lg-7 mb-4">
                    <div class="card card-custom p-4">
                        <h5 class="fw-bold text-success mb-3">Penghitung Ongkos Kirim Otomatis</h5>
                        <form action="{{ route('checkout') }}" method="POST"> @csrf
                            <input type="hidden" name="total_price" value="{{ $totalPrice ?? 0 }}">
                            <input type="hidden" name="weight" value="{{ $totalWeight ?? 0 }}">
                            
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Pilih Provinsi</label>
                                    <select class="form-select form-select-md" name="province_id" id="province_id">
                                     <option value="">-- Provinsi --</option>
                                        @if(isset($provinces))
                                        @foreach($provinces as $pr)
                                         <option value="{{ $pr['province_id'] }}">{{ $pr['province_name'] }}</option>
                                             @endforeach
                                      @endif
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-secondary">Pilih Kota Tujuan</label>
                                    <select class="form-select form-select-sm" name="city_id" id="city" required>
                                        <option value="">-- Kota --</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label small fw-semibold text-secondary">Kurir Ekspedisi Pengiriman</label>
                                    <select class="form-select form-select-sm" name="courier" required>
                                        <option value="jne">JNE Express</option>
                                        <option value="pos">POS Indonesia</option>
                                        <option value="tiki">TIKI Logistik</option>
                                    </select>
                                </div>
                            </div>
                            <button class="btn btn-success w-100 btn-sm fw-bold mt-4 py-2 shadow-sm" @if(($totalWeight ?? 0) == 0) disabled @endif>
                                Hitung Ongkir Logistik Kue
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="custom-tab-content" id="konten-status">
            <div class="shopee-tabs">
                <div class="shopee-tab-item active" id="subtab-belum" onclick="switchSubTab('belum')">Belum Bayar</div>
                <div class="shopee-tab-item" id="subtab-kirim" onclick="switchSubTab('kirim')">Dikirim</div>
                <div class="shopee-tab-item" id="subtab-selesai" onclick="switchSubTab('selesai')">Selesai</div>
            </div>

            <div class="card card-custom p-4">
                <div id="subtab-content-area">
                    <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                        <span class="small text-muted">No. Invoice: <b>CK-INV/2026/0045</b></span>
                        <span class="text-danger fw-bold small">BELUM BAYAR</span>
                    </div>
                    
                    @if(session('results'))
                        <div class="p-3 bg-light rounded mb-3">
                            <h6 class="fw-bold text-dark mb-1">Rincian Perhitungan Pembayaran Cookies:</h6>
                            <div class="d-flex justify-content-between small text-muted">
                                <span>Subtotal Cookies:</span>
                                <span>Rp {{ number_format($totalPrice ?? 0, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between small text-muted mb-2">
                                <span>Biaya Ongkos Kirim:</span>
                                <span>Rp {{ number_format(session('results')[0]['cost'], 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between fw-bold text-dark border-top pt-2">
                                <span>Total Tagihan Akhir:</span>
                                <span class="text-danger">Rp {{ number_format(($totalPrice ?? 0) + session('results')[0]['cost'], 0, ',', '.') }}</span>
                            </div>
                        </div>
                        
                        <a href="{{ url('/print-invoice?pdf=true') }}" target="_blank" class="btn btn-sm w-100 fw-bold text-white py-2" style="background-color: #8B4513;">
                            Cetak & Ekspor Resi Invoice Cookies (PDF)
                        </a>
                    @else
                        <div class="text-center py-4 text-muted small">
                            Belum ada kalkulasi ongkir aktif. Silakan lakukan hitung ongkir di tab <b>"Pilih Pengiriman"</b> terlebih dahulu.
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

    <div class="bottom-nav">
        <button class="bottom-nav-item active" id="btn-nav-beranda" onclick="pindahTab('beranda')">
            <span class="bottom-nav-icon">🏠</span>
            <span>Beranda</span>
        </button>
        <button class="bottom-nav-item" id="btn-nav-pengiriman" onclick="pindahTab('pengiriman')">
            <span class="bottom-nav-icon">🚚</span>
            <span>Pilih Pengiriman</span>
        </button>
        <button class="bottom-nav-item" id="btn-nav-status" onclick="pindahTab('status')">
            <span class="bottom-nav-icon">📦</span>
            <span>Status Transaksi</span>
        </button>
    </div>

    <script>
        // Fungsi Pop-Up Keamanan Password Akun Admin Owner
        function mintaPasswordAdmin() {
            let pass = prompt(" Sistem Keamanan Indo-Ongkir:\nMasukkan password otentikasi Admin Owner:");
            if (pass != null) {
                if (pass === "") {
                    alert("Password tidak boleh kosong!");
                } else {
                    // Redirect otomatis ke controller dengan membawa parameter query string password
                    window.location.href = "{{ url('/switch-role/Admin') }}?password=" + encodeURIComponent(pass);
                }
            }
        }

        function pindahTab(target) {
            document.getElementById('btn-nav-beranda').classList.remove('active');
            document.getElementById('btn-nav-pengiriman').classList.remove('active');
            document.getElementById('btn-nav-status').classList.remove('active');

            document.getElementById('konten-beranda').classList.remove('active');
            document.getElementById('konten-pengiriman').classList.remove('active');
            document.getElementById('konten-status').classList.remove('active');

            document.getElementById('btn-nav-' + target).classList.add('active');
            document.getElementById('konten-' + target).classList.add('active');
        }

        document.getElementById('btn-keranjang-atas').addEventListener('click', function(e) {
            e.preventDefault();
            pindahTab('pengiriman');
        });

        function switchSubTab(sub) {
            document.getElementById('subtab-belum').classList.remove('active');
            document.getElementById('subtab-kirim').classList.remove('active');
            document.getElementById('subtab-selesai').classList.remove('active');
            document.getElementById('subtab-' + sub).classList.add('active');

            let area = document.getElementById('subtab-content-area');
            if(sub === 'kirim' || sub === 'selesai') {
                area.innerHTML = `<div class="text-center py-4 text-muted small">Riwayat pengantaran pesanan cookies status [${sub.toUpperCase()}] masih kosong.</div>`;
            } else {
                window.location.reload();
            }
        }

        document.getElementById('province_id').addEventListener('change', function() {
            let citySelect = document.getElementById('city');
            citySelect.innerHTML = '<option value="">Memuat data kota...</option>';
            if (!this.value) return;

            fetch('/get-cities/' + this.value)
                .then(res => res.json())
                .then(data => {
                    let html = '<option value="">-- Pilih Kota Tujuan --</option>';
                    data.forEach(c => { html += `<option value="${c.city_id}">${c.city_name}</option>`; });
                    citySelect.innerHTML = html;
                });
        });

        let cekHasil = "{{ session('results') ? 'true' : 'false' }}";
        if (cekHasil === 'true') {
            pindahTab('status');
        }
    </script>
</body>
</html>