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
        .navbar-top { background: linear-gradient(135deg, #8B4513, #D2691E); }
        .card-custom { border: none; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.04); background-color: #ffffff; }
        .product-img { height: 160px; object-fit: cover; border-radius: 8px; }
        
        .cart-header-icon-btn {
            position: relative;
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.4);
            width: 42px; height: 42px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 50%; color: #ffffff; font-size: 20px;
            text-decoration: none; transition: all 0.2s; cursor: pointer;
        }
        .cart-header-icon-btn:hover { background: rgba(255,255,255,0.3); color: #fff; }
        .cart-badge-circle {
            position: absolute; top: -4px; right: -4px;
            background-color: #92b8fb; color: #fff;
            font-size: 10px; font-weight: bold; border-radius: 50%;
            width: 18px; height: 18px; display: flex;
            align-items: center; justify-content: center;
            border: 1px solid #8B4513;
        }

        .shopee-tabs { display: flex; border-bottom: 2px solid #e8e8e8; margin-bottom: 20px; background: #fff; border-radius: 8px; overflow: hidden; }
        .shopee-tab-item { flex: 1; text-align: center; padding: 12px 5px; font-size: 14px; color: #555; cursor: pointer; font-weight: 500; border-bottom: 3px solid transparent; }
        .shopee-tab-item.active { color: #8B4513; border-bottom-color: #8B4513; font-weight: bold; }

        .bottom-nav {
            position: fixed; bottom: 0; left: 0; right: 0; height: 65px;
            background-color: #ffffff; box-shadow: 0 -3px 12px rgba(0,0,0,0.06);
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
            <button class="cart-header-icon-btn" id="btn-keranjang-atas" title="Lihat Keranjang" onclick="switchTab('konten-pengiriman')">
                🛒
                @if(isset($cart) && count($cart) > 0)
                    <span class="cart-badge-circle">{{ count($cart) }}</span>
                @endif
            </button>
        </div>
    </nav>

    <div class="container">

        {{-- Card Switch Role --}}
        <div class="card card-custom p-3 mb-4 border shadow-sm">
            <div class="row align-items-center">
                <div class="col-md-7 mb-2 mb-md-0">
                    <span class="text-secondary d-block small">Status Hak Akses Akun Saat Ini:</span>
                    <strong class="h5">
                        {{ session('user_role') == 'Admin' ? '🔑 Admin Owner Toko' : '🛍️ Customer / Pembeli' }}
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

        {{-- Alert --}}
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-3 small shadow-sm" role="alert">
                ⚠️ {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 small shadow-sm" role="alert">
                ✅ {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Tabs --}}
        <div class="shopee-tabs mb-3">
            <div class="shopee-tab-item active" id="tab-beranda" onclick="switchTab('konten-beranda')">🏠 Beranda</div>
            <div class="shopee-tab-item" id="tab-pengiriman" onclick="switchTab('konten-pengiriman')">
                🚚 Keranjang & Ongkir
                @if(isset($cart) && count($cart) > 0)
                    <span class="badge bg-danger ms-1" style="font-size:10px;">{{ count($cart) }}</span>
                @endif
            </div>
        </div>

        {{-- TAB: BERANDA --}}
        <div class="custom-tab-content active" id="konten-beranda">

            {{-- Form Admin --}}
            @if(session('user_role') == 'Admin')
                <div class="card card-custom p-4 mb-4 border border-warning" style="background-color: #fffdf9;">
                    <div class="d-flex align-items-center mb-3">
                        <span class="fs-4 me-2">🛠️</span>
                        <h5 class="fw-bold text-dark mb-0">Input CRUD Manajemen Produk Cookies (Khusus Admin)</h5>
                    </div>
                    <form action="{{ route('product.store') }}" method="POST" class="row g-3 align-items-end">
                        @csrf
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold text-muted mb-1">Nama Varian Cookies</label>
                            <input type="text" class="form-control form-control-sm" name="name" placeholder="Misal: Premium Dubai Pistachio Cookies" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-semibold text-muted mb-1">Harga (Rp)</label>
                            <input type="number" class="form-control form-control-sm" name="harga" placeholder="65000" required>
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

            {{-- Etalase Produk --}}
            <div class="card card-custom p-4">
                <h5 class="fw-bold text-dark mb-3">🍪 Etalase Live Aneka Cookies Premium</h5>
                <div class="row">
                    @if(isset($products) && count($products) > 0)
                        @foreach($products as $p)
                        <div class="col-6 col-md-3 mb-3">
                            <div class="card h-100 border p-2 position-relative shadow-none">

                                @if(session('user_role') == 'Admin')
                                    <a href="{{ url('/product/delete/'.$p['id']) }}"
                                       class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2 rounded-circle shadow-sm"
                                       style="padding: 2px 8px; font-size: 11px;"
                                       title="Hapus Produk"
                                       onclick="return confirm('Yakin ingin menghapus produk ini?')">✕</a>
                                @endif

                                <img src="{{ $p['img'] ?? 'https://images.unsplash.com/photo-1499636136210-6f4ce91a094f?w=300' }}"
                                     class="card-img-top product-img" alt="{{ $p['name'] }}">

                                <div class="card-body p-2 d-flex flex-column justify-content-between">
                                    <div>
                                        <div class="fw-bold text-dark text-truncate mb-1" style="font-size:14px;">{{ $p['name'] }}</div>
                                        <div class="text-success fw-bold small mb-1">Rp {{ number_format($p['harga'], 0, ',', '.') }}</div>
                                        <div class="d-flex justify-content-between text-muted" style="font-size: 11px;">
                                            <span>Stok: <b>{{ $p['stock'] ?? 10 }}</b></span>
                                            <span>{{ $p['weight'] }} gr</span>
                                        </div>
                                    </div>

                                    {{-- ✅ FIX: name="product_id" bukan name="id" --}}
                                    <form action="{{ route('cart.add') }}" method="POST" class="mt-2">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $p['id'] }}">
                                        <input type="hidden" name="name" value="{{ $p['name'] }}">
                                        <input type="hidden" name="price" value="{{ $p['harga'] }}">
                                        <input type="hidden" name="weight" value="{{ $p['weight'] }}">
                                        <button class="btn btn-outline-warning text-dark w-100 btn-sm fw-bold"
                                                style="font-size: 11px; background-color: #fff8ee;">+ Keranjang</button>
                                    </form>
                                </div>

                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-4 text-muted small">Belum ada produk tersedia.</div>
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
                            {{-- ✅ FIX: cart.clear pakai POST form bukan <a href> --}}
                            <form action="{{ route('cart.clear') }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Kosongkan semua keranjang?')">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-link text-decoration-none text-danger p-0 small">
                                    🗑️ Reset
                                </button>
                            </form>
                        </div>

                        @if(isset($cart) && count($cart) > 0)
                            <div class="p-2 border rounded bg-light mb-3" style="max-height: 280px; overflow-y:auto;">
                                @foreach($cart as $key => $c)
                                    @php $idItem = $key; @endphp
                                    <div class="d-flex justify-content-between align-items-center small mb-2 border-bottom pb-2">
                                        <div class="text-truncate" style="max-width: 150px;">
                                            <div class="fw-bold text-dark text-truncate">{{ $c['name'] ?? 'Cookies' }}</div>
                                            <small class="text-muted">Rp {{ number_format($c['price'] ?? 0, 0, ',', '.') }}</small>
                                        </div>

                                        <div class="d-flex align-items-center">
                                            <div class="btn-group btn-group-sm me-2" role="group">
                                                {{-- ✅ FIX: pakai route name --}}
                                                <a href="{{ route('cart.decrease', $idItem) }}" class="btn btn-outline-secondary px-2 py-0 fw-bold" style="font-size:10px;">➖</a>
                                                <span class="btn btn-light px-2 py-0 disabled fw-bold text-dark" style="font-size:12px;">{{ $c['qty'] ?? 1 }}</span>
                                                <a href="{{ route('cart.increase', $idItem) }}" class="btn btn-outline-secondary px-2 py-0 fw-bold" style="font-size:10px;">➕</a>
                                            </div>
                                            <span class="fw-bold text-dark me-2 small">Rp {{ number_format(($c['price'] ?? 0) * ($c['qty'] ?? 1), 0, ',', '.') }}</span>
                                            <a href="{{ route('cart.delete', $idItem) }}" class="btn btn-sm btn-link text-danger p-0" style="font-size: 15px; text-decoration: none;">🗑️</a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="d-flex justify-content-between small mb-1">
                                <span class="text-muted">Total Berat:</span>
                                <span class="fw-bold text-dark">{{ $totalWeight ?? 0 }} Gram</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Subtotal:</span>
                                <span class="fw-bold text-danger fs-5">Rp {{ number_format($totalPrice ?? 0, 0, ',', '.') }}</span>
                            </div>
                        @else
                            <div class="text-center py-5 text-muted small">🍪 Keranjang kue kosong.</div>
                        @endif
                    </div>
                </div>

                {{-- Cek Ongkir --}}
                <div class="col-lg-7 mb-4">
                    <div class="card card-custom p-4">
                        <h5 class="fw-bold text-success mb-3">🚚 Penghitung Ongkos Kirim Otomatis</h5>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Provinsi Tujuan</label>
                            <select class="form-select form-select-sm" id="province-select">
                                <option value="">-- Pilih Provinsi --</option>
                                @foreach($provinces as $prov)
                                    <option value="{{ $prov['province_id'] }}">{{ $prov['province_name'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Kota / Kabupaten Tujuan</label>
                            <select class="form-select form-select-sm" id="city-select" disabled>
                                <option value="">-- Pilih Kota --</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Kurir</label>
                            <select class="form-select form-select-sm" id="courier-select">
                                <option value="jne">JNE</option>
                                <option value="tiki">TIKI</option>
                                <option value="pos">POS Indonesia</option>
                            </select>
                        </div>

                        <button class="btn btn-success btn-sm w-100 fw-bold" id="btn-cek-ongkir" onclick="cekOngkir()">
                            🔍 Cek Ongkos Kirim
                        </button>

                        <div id="hasil-ongkir" class="mt-3"></div>
                    </div>
                </div>

            </div>
        </div>

    </div>

    {{-- Bottom Nav --}}
    <div class="bottom-nav">
        <button class="bottom-nav-item active" id="nav-beranda" onclick="switchTab('konten-beranda')">
            <span class="bottom-nav-icon">🏠</span> Beranda
        </button>
        <button class="bottom-nav-item" id="nav-pengiriman" onclick="switchTab('konten-pengiriman')">
            <span class="bottom-nav-icon">🚚</span> Ongkir
        </button>
        <form action="/logout" method="POST" class="bottom-nav-item p-0 m-0">
            @csrf
            <button type="submit" class="bottom-nav-item">
                <span class="bottom-nav-icon">🚪</span> Logout
            </button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        
        function switchTab(tabId) {
            document.querySelectorAll('.custom-tab-content').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('.shopee-tab-item').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('.bottom-nav-item').forEach(el => el.classList.remove('active'));

            document.getElementById(tabId).classList.add('active');

            if (tabId === 'konten-beranda') {
                document.getElementById('tab-beranda').classList.add('active');
                document.getElementById('nav-beranda').classList.add('active');
            } else {
                document.getElementById('tab-pengiriman').classList.add('active');
                document.getElementById('nav-pengiriman').classList.add('active');
            }
        }

        
        document.getElementById('province-select').addEventListener('change', function () {
            const provinceId = this.value;
            const citySelect = document.getElementById('city-select');

            citySelect.innerHTML = '<option>Memuat kota...</option>';
            citySelect.disabled = true;

            if (!provinceId) {
                citySelect.innerHTML = '<option value="">-- Pilih Kota --</option>';
                return;
            }

            fetch(`/get-cities/${provinceId}`)
                .then(res => res.json())
                .then(data => {
                    citySelect.innerHTML = '<option value="">-- Pilih Kota --</option>';
                    data.forEach(city => {
                        citySelect.innerHTML += `<option value="${city.city_id}">${city.city_name}</option>`;
                    });
                    citySelect.disabled = false;
                })
                .catch(() => {
                    citySelect.innerHTML = '<option value="">Gagal memuat kota</option>';
                });
        });

        function cekOngkir() {
            const destination = document.getElementById('city-select').value;
            const courier     = document.getElementById('courier-select').value;
            const weight      = {{ $totalWeight ?? 300 }};
            const hasil       = document.getElementById('hasil-ongkir');

            if (!destination) {
                hasil.innerHTML = '<div class="alert alert-warning small">Pilih kota tujuan terlebih dahulu.</div>';
                return;
            }

            hasil.innerHTML = '<div class="text-center text-muted small py-2">⏳ Mengecek ongkir...</div>';

            fetch('/check-cost', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ destination, courier, weight })
            })
            .then(res => res.json())
            .then(data => {
                if (!data || data.length === 0) {
                    hasil.innerHTML = '<div class="alert alert-warning small">Tidak ada layanan tersedia.</div>';
                    return;
                }

                let html = '<div class="list-group">';
                data.forEach(item => {
                    const costs = item.costs ?? [];
                    costs.forEach(cost => {
                        const value = cost.cost?.[0]?.value ?? 0;
                        const etd   = cost.cost?.[0]?.etd ?? '-';
                        html += `
                            <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-2">
                                <div>
                                    <span class="fw-bold text-uppercase">${courier}</span>
                                    <span class="badge bg-secondary ms-1">${cost.service}</span>
                                    <div class="text-muted" style="font-size:11px;">${cost.description} • ${etd} hari</div>
                                </div>
                                <span class="fw-bold text-success">Rp ${value.toLocaleString('id-ID')}</span>
                            </div>`;
                    });
                });
                html += '</div>';
                hasil.innerHTML = html;
            })
            .catch(() => {
                hasil.innerHTML = '<div class="alert alert-danger small">Gagal mengambil data ongkir.</div>';
            });
        }

        function mintaPasswordAdmin() {
            const pass = prompt('Masukkan password Admin Owner:');
            if (pass === 'admin123') {
                window.location.href = '{{ url("/switch-role/Admin") }}';
            } else if (pass !== null) {
                alert('Password salah!');
            }
        }
    </script>

</body>
</html>