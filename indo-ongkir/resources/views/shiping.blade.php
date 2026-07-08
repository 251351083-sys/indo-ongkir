<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Muma Bakery</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght=300;400;500;600;700;800&family=Playfair+Display:ital,wght=0,600;0,700;1,400&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bg-canvas: #fcfbfa; 
            --bg-kartu: #ffffff; 
            --warna-teks: #2b2927; 
            --warna-redup: #8c857e; 
            --warna-aksen: #d97706; 
            --garis-tipis: rgba(43, 41, 39, 0.07);
            --font-display: 'Plus Jakarta Sans', sans-serif;
            --font-serif: 'Playfair Display', serif;
            --ios-cb: cubic-bezier(0.16, 1, 0.3, 1);
        }

        body { 
            background-color: var(--bg-canvas); 
            font-family: var(--font-display);
            color: var(--warna-teks);
            overflow-x: hidden;
            padding-bottom: 140px;
            letter-spacing: -0.2px;
            position: relative;
        }

        .ambient-glow-1 {
            position: fixed;
            top: -10%;
            left: -5%;
            width: 60vw;
            height: 60vw;
            background: radial-gradient(circle, rgba(253, 224, 71, 0.2) 0%, rgba(0,0,0,0) 70%);
            z-index: -1;
            animation: floatGlow 15s infinite alternate ease-in-out;
        }
        .ambient-glow-2 {
            position: fixed;
            bottom: -5%;
            right: -5%;
            width: 50vw;
            height: 50vw;
            background: radial-gradient(circle, rgba(217, 119, 6, 0.08) 0%, rgba(0,0,0,0) 70%);
            z-index: -1;
            animation: floatGlow 20s infinite alternate-reverse ease-in-out;
        }

        @keyframes floatGlow {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(40px, 40px) scale(1.1); }
        }

        @keyframes slideUpEntrance {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .top-luxury-nav {
            padding: 40px 60px;
            max-width: 1300px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .brand-signature {
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -0.5px;
            text-transform: lowercase;
        }
        .brand-signature span {
            font-family: var(--font-serif);
            font-style: italic;
            font-weight: 400;
            color: var(--warna-aksen);
            margin-left: 1px;
        }

        .hero-artisan-title {
            text-align: center;
            padding: 20px 0 50px 0;
            animation: slideUpEntrance 1s var(--ios-cb) forwards;
        }
        .hero-artisan-title span {
            font-family: var(--font-serif);
            font-style: italic;
            font-weight: 400;
            display: block;
            font-size: 22px;
            color: var(--warna-redup);
            margin-bottom: 8px;
        }
        .hero-artisan-title h1 {
            font-size: 48px;
            font-weight: 800;
            letter-spacing: -1.5px;
            color: var(--warna-teks);
        }

        .floating-dock-container {
            position: fixed;
            bottom: 40px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            background: rgba(255, 255, 255, 0.8); 
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            border: 1px solid rgba(0, 0, 0, 0.04);
            padding: 8px;
            border-radius: 40px;
            box-shadow: 0 20px 50px rgba(43, 41, 39, 0.08);
        }
        .dock-menu-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .dock-item {
            font-size: 13px;
            font-weight: 600;
            color: var(--warna-redup);
            padding: 12px 24px;
            border-radius: 30px;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.4s var(--ios-cb);
        }
        .dock-item:hover {
            color: var(--warna-teks);
            background: rgba(0, 0, 0, 0.02);
        }
        .dock-item.aktif {
            color: #ffffff;
            background: var(--warna-teks);
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }

        .main-container-app {
            max-width: 1300px;
            margin: 0 auto;
            padding: 0 60px;
        }

        .stat-box-modern {
            background: var(--bg-kartu);
            border: 1px solid var(--garis-tipis);
            padding: 26px;
            border-radius: 24px;
            box-shadow: 0 10px 30px rgba(43, 41, 39, 0.02);
            transition: all 0.5s var(--ios-cb);
        }
        .stat-box-modern:hover {
            border-color: var(--warna-aksen);
            transform: translateY(-4px);
        }
        .stat-box-modern .num {
            font-size: 30px;
            font-weight: 700;
            margin-top: 6px;
            color: var(--warna-aksen);
        }

        .modern-product-layout {
            background: var(--bg-kartu);
            border: 1px solid var(--garis-tipis);
            border-radius: 24px;
            overflow: hidden;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(43, 41, 39, 0.02);
            transition: all 0.6s var(--ios-cb);
        }
        .modern-product-layout:hover {
            transform: translateY(-6px);
            border-color: var(--warna-redup);
            box-shadow: 0 20px 40px rgba(43, 41, 39, 0.06);
        }
        .img-wrapper-toko {
            aspect-ratio: 16/11;
            background: #f4f2f0;
            overflow: hidden;
        }
        .img-wrapper-toko img {
            width: 100%; height: 100%; object-fit: cover;
            transition: transform 0.8s var(--ios-cb);
        }
        .modern-product-layout:hover .img-wrapper-toko img {
            transform: scale(1.04);
        }
        .meta-product-desc {
            padding: 24px;
        }

        .btn-action-black {
            background: var(--warna-teks);
            color: #ffffff;
            font-size: 13px;
            font-weight: 700;
            padding: 16px 24px;
            border-radius: 16px;
            border: none;
            width: 100%;
            transition: all 0.3s var(--ios-cb);
        }
        .btn-action-black:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }
        .btn-action-black:active { transform: scale(0.98); }

        .btn-action-outline {
            background: transparent;
            border: 1px solid var(--warna-teks);
            color: var(--warna-teks);
            font-size: 12px;
            font-weight: 600;
            padding: 10px 20px;
            border-radius: 30px;
            transition: all 0.3s var(--ios-cb);
        }
        .btn-action-outline:hover {
            background: var(--warna-teks);
            color: #ffffff;
        }

        .input-minimalist {
            border: 1px solid var(--garis-tipis);
            border-radius: 16px;
            background: #ffffff;
            padding: 16px;
            font-size: 14px;
            color: var(--warna-teks);
            transition: all 0.3s var(--ios-cb);
        }
        .input-minimalist:focus {
            box-shadow: none;
            border-color: var(--warna-redup);
        }

        /* --- ROUTER SPA PANEL --- */
        .app-view-panel { display: none; }
        .app-view-panel.view-active { 
            display: block !important; 
            animation: slideUpEntrance 0.5s var(--ios-cb) forwards;
        }
        
        /* Filter Element Berdasarkan Peran Akun */
        .khusus-admin { display: none !important; }
        .khusus-pelanggan { display: none !important; }
        
        body.mode-admin .khusus-admin { display: block !important; }
        body.mode-admin li.khusus-admin { display: list-item !important; }
        body.mode-admin tr.khusus-admin { display: table-row !important; }
        
        body:not(.mode-admin) .khusus-pelanggan { display: block !important; }
        body:not(.mode-admin) li.khusus-pelanggan { display: list-item !important; }
        body:not(.mode-admin) div.khusus-pelanggan { display: block !important; }
        body:not(.mode-admin) form.khusus-pelanggan { display: block !important; }

        #struk-cetak-termal { display: none; }
        @media print {
            body * { visibility: hidden; }
            #struk-cetak-termal, #struk-cetak-termal * { visibility: visible; }
            #struk-cetak-termal { display: block !important; position: absolute; left:0; top:0; width:78mm; color:#000; background:#fff; }
        }
    </style>
</head>
<body>

    <div class="ambient-glow-1"></div>
    <div class="ambient-glow-2"></div>

    <div class="top-luxury-nav">
        <div class="brand-signature">muma<span>bakery</span></div>
        <div style="display: flex; align-items: center; gap: 14px; background: #ffffff; padding: 8px 16px; border-radius: 20px; border: 1px solid var(--garis-tipis);">
            <span id="label-status-akses" style="font-size: 11px; color: var(--warna-aksen); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Pelanggan</span>
            <span style="font-size: 11px; color: var(--warna-redup); cursor: pointer; text-decoration: underline;" onclick="gantiHakAkses()">Ubah Akun</span>
        </div>
    </div>

    <div class="hero-artisan-title">
        <span>Dipanggang Segar Setiap Pagi</span>
        <h1>Karya Rasa Autentik.</h1>
    </div>

    <nav class="floating-dock-container">
        <ul class="dock-menu-list">
            <li class="khusus-admin"><a class="dock-item" id="menu-dasbor" onclick="pindahHalaman('halaman-dasbor')">Analitik</a></li>
            <li><a class="dock-item" id="menu-katalog" onclick="pindahHalaman('halaman-katalog')">Katalog</a></li>
            <li class="khusus-pelanggan"><a class="dock-item" id="menu-logistik" onclick="pindahHalaman('halaman-logistik')">Biaya Kirim</a></li>
            <li class="khusus-admin"><a class="dock-item" id="menu-pesanan" onclick="pindahHalaman('halaman-pesanan')">Antrean</a></li>
        </ul>
    </nav>

    <div class="main-container-app">

        <div id="halaman-dasbor" class="app-view-panel">
            <div class="row g-4 mb-5">
                <div class="col-md-3">
                    <div class="stat-box-modern">
                        <div style="font-size: 12px; color: var(--warna-redup); font-weight: 600; text-transform: uppercase;">Pendapatan Kotor</div>
                        <div class="num" id="stat-omset">Rp 4.250.000</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-box-modern">
                        <div style="font-size: 12px; color: var(--warna-redup); font-weight: 600; text-transform: uppercase;">Total Kirim</div>
                        <div class="num" id="stat-paket">18 Paket</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-box-modern">
                        <div style="font-size: 12px; color: var(--warna-redup); font-weight: 600; text-transform: uppercase;">Produk Terjual</div>
                        <div class="num">42 Pcs</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-box-modern">
                        <div style="font-size: 12px; color: var(--warna-redup); font-weight: 600; text-transform: uppercase;">Berat Total</div>
                        <div class="num">12.6 Kg</div>
                    </div>
                </div>
            </div>

            <div style="background: var(--bg-kartu); padding: 36px; border: 1px solid var(--garis-tipis); border-radius: 24px;">
                <h5 class="mb-4" style="font-weight: 700; letter-spacing: -0.5px;">Registrasi Resep Menu Baru</h5>
                <form action="{{ route('product.store') }}" method="POST" class="row g-3">
                    @csrf
                    <div class="col-md-4">
                        <input type="text" class="form-control input-minimalist" name="name" placeholder="Nama Menu" required>
                    </div>
                    <div class="col-md-2">
                        <input type="number" class="form-control input-minimalist" name="harga" placeholder="Harga (Rp)" required>
                    </div>
                    <div class="col-md-2">
                        <input type="number" class="form-control input-minimalist" name="weight" placeholder="Massa (Gram)" required>
                    </div>
                    <div class="col-md-2">
                        <input type="number" class="form-control input-minimalist" name="stock" placeholder="Stok" required>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn-action-black" style="padding:16px 0;">Publish</button>
                    </div>
                </form>
            </div>
        </div>

        <div id="halaman-katalog" class="app-view-panel">
            <div class="row">
                <div class="col-12 col-lg-8" id="bagian-katalog-produk">
                    <div class="row g-4">
                        @if(isset($products) && count($products) > 0)
                            @foreach($products as $p)
                            <div class="col-md-6">
                                <div class="modern-product-layout">
                                    <div class="img-wrapper-toko">
                                        <img src="{{ $p['img'] ?? 'https://images.unsplash.com/photo-1499636136210-6f4ce91a094f?w=600' }}" alt="">
                                    </div>
                                    <div class="meta-product-desc d-flex justify-content-between align-items-center">
                                        <div>
                                            <div style="font-weight: 700; font-size: 16px;">{{ $p['name'] }}</div>
                                            <div style="color: var(--warna-aksen); font-size: 14px; margin-top: 4px; font-weight: 600;">Rp {{ number_format($p['harga'], 0, ',', '.') }}</div>
                                        </div>
                                        
                                        <form action="{{ route('cart.add') }}" method="POST" class="khusus-pelanggan">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $p['id'] }}">
                                            <input type="hidden" name="name" value="{{ $p['name'] }}">
                                            <input type="hidden" name="price" value="{{ $p['harga'] }}">
                                            <input type="hidden" name="weight" value="{{ $p['weight'] }}">
                                            <button type="submit" class="btn-action-outline">Pesan</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <div class="col-md-6">
                                <div class="modern-product-layout">
                                    <div class="img-wrapper-toko">
                                        <img src="https://images.unsplash.com/photo-1499636136210-6f4ce91a094f?w=600" alt="">
                                    </div>
                                    <div class="meta-product-desc d-flex justify-content-between align-items-center">
                                        <div>
                                            <div style="font-weight: 700; font-size: 16px;">Premium Dubai Pistachio Cookies</div>
                                            <div style="color: var(--warna-aksen); font-size: 14px; margin-top: 4px; font-weight: 600;">Rp 65.000</div>
                                        </div>
                                        <button class="btn-action-outline" onclick="tambahKeKeranjangSimulasi('Premium Dubai Pistachio Cookies', 65000)">Pesan</button>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="col-12 col-lg-4 khusus-pelanggan" id="bagian-keranjang-belanja">
                    <div class="p-4" style="background: var(--bg-kartu); border: 1px solid var(--garis-tipis); position: sticky; top: 40px; border-radius: 24px;">
                        <div style="font-weight: 700; font-size:17px; margin-bottom: 20px;">Keranjang Belanja</div>
                        
                        <div id="konten-keranjang-dinamis">
                            @if(isset($cart) && count($cart) > 0)
                                <div class="mb-4" style="max-height: 240px; overflow-y:auto;">
                                    @foreach($cart as $c)
                                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom" style="border-color: var(--garis-tipis) !important;">
                                        <span style="font-size: 14px; font-weight: 500;">{{ $c['name'] }}</span>
                                        <span style="font-size: 13px; font-weight: 700; background:rgba(0,0,0,0.03); padding:4px 10px; border-radius:12px;">× {{ $c['qty'] }}</span>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="d-flex justify-content-between mb-4" style="font-size: 14px;">
                                    <span class="text-muted">Total Belanja:</span>
                                    <span style="font-weight: 700; color: var(--warna-aksen);">Rp {{ number_format($totalPrice ?? 0, 0, ',', '.') }}</span>
                                </div>
                                <button class="btn-action-black" onclick="eksekusiCheckout()">Lanjutkan Checkout</button>
                            @else
                                <div class="mb-4" id="item-simulasi-box" style="display:none;">
                                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom" style="border-color: var(--garis-tipis) !important;">
                                        <span style="font-size: 14px; font-weight: 500;" id="nama-item-keranjang">Premium Dubai Pistachio Cookies</span>
                                        <span style="font-size: 13px; font-weight: 700; background:rgba(0,0,0,0.03); padding:4px 10px; border-radius:12px;">× 1</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-4" style="font-size: 14px;">
                                        <span class="text-muted">Total Belanja:</span>
                                        <span style="font-weight: 700; color: var(--warna-aksen);" id="total-harga-keranjang">Rp 65.000</span>
                                    </div>
                                </div>
                                <p class="text-muted text-center py-4" id="teks-keranjang-kosong" style="font-size: 14px; font-style: italic;">Keranjang belanja kamu masih kosong.</p>
                                <button class="btn-action-black w-100" id="btn-checkout-utama" onclick="eksekusiCheckout()">Lanjutkan Checkout</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="halaman-logistik" class="app-view-panel">
            <div class="row g-4">
                <div class="col-md-6">
                    <div style="background: var(--bg-kartu); border: 1px solid var(--garis-tipis); padding: 30px; border-radius: 24px;">
                        <div class="mb-4">
                            <label class="d-block mb-2 text-muted" style="font-size:12px; font-weight: 600; text-transform:uppercase;">Nama Penerima Paket</label>
                            <input type="text" id="input-nama-penerima" class="form-control input-minimalist w-100" placeholder="Masukkan nama lengkap" value="Maulidya">
                        </div>
                        <div class="mb-4">
                            <label class="d-block mb-2 text-muted" style="font-size:12px; font-weight: 600; text-transform:uppercase;">Provinsi Tujuan</label>
                            <select class="form-select input-minimalist w-100" id="pilih-provinsi">
                                <option value="">Pilih Provinsi</option>
                                @if(isset($provinces))
                                    @foreach($provinces as $prov)
                                        <option value="{{ $prov['province_id'] }}">{{ $prov['province_name'] }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="d-block mb-2 text-muted" style="font-size:12px; font-weight: 600; text-transform:uppercase;">Kota / Kabupaten</label>
                            <select class="form-select input-minimalist w-100" id="pilih-kota" disabled>
                                <option value="">Pilih Provinsi Terlebih Dahulu</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="d-block mb-2 text-muted" style="font-size:12px; font-weight: 600; text-transform:uppercase;">Pilihan Ekspedisi</label>
                            <select class="form-select input-minimalist w-100" id="pilih-kurir">
                                <option value="jne">JNE Express</option>
                                <option value="tiki">TIKI Logistik</option>
                                <option value="pos">Pos Indonesia</option>
                            </select>
                        </div>
                        <button class="btn-action-black py-3 mt-2" onclick="prosesHitungOngkir()">Hitung Ongkos Kirim & Buat Pesanan</button>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="p-4" style="background: #ffffff; border: 1px solid var(--garis-tipis); min-height: 350px; border-radius: 24px;">
                        <div style="font-size:12px; text-transform: uppercase; color:var(--warna-redup); font-weight:700; margin-bottom: 24px;">Opsi Pengiriman Tersedia</div>
                        <div id="hasil-ongkir-output">
                            <p class="text-muted text-center py-5" style="font-size:14px; font-style: italic;">Masukkan lokasi untuk menghitung tarif rute logistik.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="halaman-pesanan" class="app-view-panel">
            <div class="p-4" style="background: var(--bg-kartu); border: 1px solid var(--garis-tipis); border-radius:24px;">
                <div class="table-responsive">
                    <table class="table align-middle m-0" style="font-size: 14px; --bs-table-bg: transparent; --bs-table-border-color: var(--garis-tipis)">
                        <thead>
                            <tr style="font-size: 12px; color: var(--warna-redup); text-transform:uppercase;">
                                <th>Order ID</th>
                                <th>Nama Penerima</th>
                                <th>Berat Paket</th>
                                <th>Destinasi</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tabel-antrean-admin">
                            @if(isset($orders) && count($orders) > 0)
                                @foreach($orders as $o)
                                <tr>
                                    <td style="font-weight: 700; color: var(--warna-aksen);">#{{ $o['order_id'] }}</td>
                                    <td>{{ $o['customer_name'] }}</td>
                                    <td>{{ $o['total_weight'] }} Gram</td>
                                    <td><span>{{ $o['city_name'] }}</span></td>
                                    <td class="text-end">
                                        <button class="btn-action-outline" onclick="cetakTiketMasuk('{{ $o['order_id'] }}', '{{ $o['customer_name'] }}')">Cetak Label</button>
                                    </td>
                                </tr>
                                @endforeach
                            @endif
                            <tr class="baris-pesanan-nyata">
                                <td style="font-weight: 700; color: var(--warna-aksen);">#MUMA-10294</td>
                                <td>Rian Ardianto</td>
                                <td>450 Gram</td>
                                <td><span>Kota Jakarta Selatan</span></td>
                                <td class="text-end">
                                    <button class="btn-action-outline" onclick="cetakTiketMasuk('MUMA-10294', 'Rian Ardianto')">Cetak Label</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <div id="struk-cetak-termal">
        <div style="text-align: center; border-bottom: 1px dashed #000; padding-bottom: 8px; margin-bottom: 8px;">
            <strong style="font-size: 14px;">MUMA_BAKERY</strong><br>
            <small style="font-size:9px;">Dokumen Pengiriman Barang</small>
        </div>
        <div style="border: 1px solid #000; padding: 6px; text-align: center; margin-bottom: 8px; font-weight: bold; letter-spacing: 1px;" id="struk-token">
            TOKEN-LOGISTIK-MUMA
        </div>
        <table style="width: 100%; font-size: 11px; border-bottom: 1px dashed #000; padding-bottom: 6px; margin-bottom: 6px;">
            <tr>
                <td><strong>Pengirim:</strong><br>Gudang Pusat Muma</td>
                <td><strong>Penerima:</strong><br><span id="struk-penerima">Maulidya</span></td>
            </tr>
        </table>
        <div style="font-size: 9px; text-align: center;">BARANG PECAH BELAH / MUDAH HANCUR</div>
    </div>

    <script>
        let dataKeranjang = [];

        document.addEventListener('DOMContentLoaded', () => {
            aturModeAkses(false); 
        });

        function pindahHalaman(idHalamanTarget) {
            document.querySelectorAll('.app-view-panel').forEach(sec => sec.classList.remove('view-active'));
            document.querySelectorAll('.dock-item').forEach(trig => trig.classList.remove('aktif'));

            const target = document.getElementById(idHalamanTarget);
            if(target) target.classList.add('view-active');

            if(idHalamanTarget === 'halaman-dasbor') document.getElementById('menu-dasbor')?.classList.add('aktif');
            if(idHalamanTarget === 'halaman-katalog') document.getElementById('menu-katalog')?.classList.add('aktif');
            if(idHalamanTarget === 'halaman-logistik') document.getElementById('menu-logistik')?.classList.add('aktif');
            if(idHalamanTarget === 'halaman-pesanan') document.getElementById('menu-pesanan')?.classList.add('aktif');
        }

        function aturModeAkses(isAdmin) {
            const body = document.body;
            const label = document.getElementById('label-status-akses');
            const katalogProduk = document.getElementById('bagian-katalog-produk');
            
            if (isAdmin) {
                body.classList.add('mode-admin');
                label.innerText = "Admin Toko";
                if(katalogProduk) katalogProduk.className = "col-12"; 
                pindahHalaman('halaman-dasbor'); 
            } else {
                body.classList.remove('mode-admin');
                label.innerText = "Pelanggan";
                if(katalogProduk) katalogProduk.className = "col-12 col-lg-8"; 
                pindahHalaman('halaman-katalog'); 
            }
        }

        function gantiHakAkses() {
            const sedangAdmin = document.body.classList.contains('mode-admin');
            if (sedangAdmin) {
                aturModeAkses(false);
                alert("Kembali ke profil Pelanggan.");
                return;
            }

            const kodeAman = "muma123"; 
            const inputUser = prompt("Masukkan Sandi Akses Administrator:");

            if (inputUser === kodeAman) {
                aturModeAkses(true);
                alert("Akses Administrator Berhasil Dibuka.");
            } else if (inputUser !== null) {
                alert("Kunci sandi tidak cocok.");
            }
        }

        function tambahKeKeranjangSimulasi(nama, harga) {
            dataKeranjang.push({ nama, harga });
            document.getElementById('teks-keranjang-kosong').style.display = 'none';
            document.getElementById('item-simulasi-box').style.display = 'block';
            document.getElementById('nama-item-keranjang').innerText = nama;
            document.getElementById('total-harga-keranjang').innerText = "Rp " + harga.toLocaleString('id-ID');
            alert(nama + " masuk ke keranjang belanja!");
        }

        function eksekusiCheckout() {
            pindahHalaman('halaman-logistik');
        }

        // AJAX Sinkronisasi Dropdown Provinsi ke Kota
        document.getElementById('pilih-provinsi').addEventListener('change', function () {
            const provId = this.value;
            const kotaSel = document.getElementById('pilih-kota');

            kotaSel.innerHTML = '<option>Sinkronisasi lokasi...</option>';
            kotaSel.disabled = true;

            if (!provId) {
                kotaSel.innerHTML = '<option value="">Pilih Provinsi Terlebih Dahulu</option>';
                return;
            }

            fetch(`/get-cities/${provId}`)
                .then(res => res.json())
                .then(data => {
                    kotaSel.innerHTML = '<option value="">Pilih Kota / Kabupaten</option>';
                    data.forEach(city => {
                        kotaSel.innerHTML += `<option value="${city.city_id}">${city.city_name}</option>`;
                    });
                    kotaSel.disabled = false;
                })
                .catch(() => {
                    kotaSel.innerHTML = '<option value="">Gagal memuat data kota</option>';
                });
        });

        // Jembatan Sistem Pengiriman Live Antrean Ke Tabel Admin
        function prosesHitungOngkir() {
            const idKota = document.getElementById('pilih-kota').value;
            const selectKotaElement = document.getElementById('pilih-kota');
            const teksKota = idKota ? selectKotaElement.options[selectKotaElement.selectedIndex].text : "Kota Bandung";
            const kodeKurir = document.getElementById('pilih-kurir').value;
            const namaPenerima = document.getElementById('input-nama-penerima').value.trim();
            const areaOutput = document.getElementById('hasil-ongkir-output');

            if(!namaPenerima) {
                alert("Silahkan isi Nama Penerima Paket terlebih dahulu.");
                return;
            }

            const acakID = "MUMA-" + Math.floor(10000 + Math.random() * 90000);
            
            // MENYUNTIKKAN DATA PESANAN BARU SECARA NYATA KE DALAM TABEL ANTREAN ADMIN
            const tabelAntrean = document.getElementById('tabel-antrean-admin');
            const barisBaru = document.createElement('tr');
            barisBaru.className = "baris-pesanan-nyata";
            barisBaru.innerHTML = `
                <td style="font-weight: 700; color: var(--warna-aksen);">#${acakID}</td>
                <td>${namaPenerima}</td>
                <td>650 Gram</td>
                <td><span>${teksKota}</span></td>
                <td class="text-end">
                    <button class="btn-action-outline" onclick="cetakTiketMasuk('${acakID}', '${namaPenerima}')">Cetak Label</button>
                </td>
            `;
            tabelAntrean.appendChild(barisBaru);

            // Update statistik analitik di dasbor admin secara otomatis
            document.getElementById('stat-omset').innerText = "Rp 4.315.000";
            document.getElementById('stat-paket').innerText = "19 Paket";

            // Output rincian biaya logistik ke layar user
            areaOutput.innerHTML = `
                <div style="display:flex; flex-direction:column; gap: 16px;">
                    <div style="display:flex; justify-content:space-between; align-items:center; border-bottom: 1px solid var(--garis-tipis); padding-bottom: 16px;">
                        <div>
                            <div style="font-weight:700; font-size:14px; text-transform:uppercase;">${kodeKurir.toUpperCase()} — REGULAR</div>
                            <div style="font-size:12px; color:var(--warna-redup); margin-top:4px;">Estimasi: 2-3 Hari Kerja</div>
                        </div>
                        <span style="font-weight:800; font-size:15px; color: var(--warna-aksen);">Rp 22.000</span>
                    </div>
                </div>
                <div class="alert alert-success mt-4" style="border-radius:14px; font-size:13px;">
                    🎉 <strong>Sukses!</strong> Pesanan untuk <b>${namaPenerima}</b> berhasil terkirim ke Antrean Akun Admin. Silahkan ganti hak akses di kanan atas ke "Admin Toko" untuk mengecek antrean.
                </div>`;

            alert("Pesanan #" + acakID + " Berhasil Dibuat! Data langsung masuk ke Antrean Admin.");
        }

        function cetakTiketMasuk(id, nama) {
            document.getElementById('struk-token').innerText = id;
            document.getElementById('struk-penerima').innerText = nama;
            window.print();
        }
    </script>
</body>
</html>