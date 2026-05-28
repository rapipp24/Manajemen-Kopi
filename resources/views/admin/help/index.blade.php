<x-layouts.admin>
    <x-slot name="title">Pusat Bantuan & Panduan Penggunaan</x-slot>

    <style>
        /* ── Theme & Layout ── */
        .help-container {
            max-width: 1200px;
            margin: 0 auto 50px auto;
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 28px;
        }

        /* ── Sidebar Navigation ── */
        .help-sidebar {
            position: sticky;
            top: 20px;
            height: fit-content;
            background: white;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            padding: 20px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .help-sidebar-title {
            font-size: 13px;
            font-weight: 800;
            color: #92400e;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 1.5px solid #fffbeb;
        }

        .help-nav-list {
            display: flex;
            flex-direction: column;
            gap: 6px;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .help-nav-item a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            color: #64748b;
            text-decoration: none;
            transition: all 0.2s;
        }

        .help-nav-item a:hover,
        .help-nav-item.active a {
            background: #fffbeb;
            color: #92400e;
        }

        /* ── Main Content Area ── */
        .help-content {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .help-section-card {
            background: white;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            padding: 28px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            scroll-margin-top: 20px;
        }

        .help-section-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
            padding-bottom: 14px;
            border-bottom: 1px solid #f1f5f9;
        }

        .help-icon-box {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: #fffbeb;
            border: 1px solid #fef3c7;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #92400e;
            flex-shrink: 0;
        }

        .help-section-title {
            font-size: 16px;
            font-weight: 800;
            color: #1e293b;
            margin: 0;
        }

        /* ── Step List & UI Elements ── */
        .step-timeline {
            position: relative;
            padding-left: 24px;
            border-left: 2px dashed #f0dcc8;
            margin-left: 10px;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .step-node {
            position: relative;
        }

        .step-node::before {
            content: '';
            position: absolute;
            left: -29px;
            top: 4px;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #b45309;
            border: 2px solid white;
            box-shadow: 0 0 0 3px #fde68a;
        }

        .step-title {
            font-size: 13.5px;
            font-weight: 700;
            color: #78350f;
            margin-bottom: 4px;
        }

        .step-desc {
            font-size: 12.5px;
            color: #475569;
            line-height: 1.6;
        }

        .alert-box {
            background: #fffdf5;
            border: 1px solid #fde68a;
            border-radius: 12px;
            padding: 16px;
            display: flex;
            gap: 12px;
            margin-top: 16px;
        }

        .alert-box.danger {
            background: #fff5f5;
            border-color: #fee2e2;
        }

        .alert-text {
            font-size: 12.5px;
            line-height: 1.5;
            color: #78350f;
        }

        .alert-box.danger .alert-text {
            color: #991b1b;
        }

        /* ── Grid/Column layouts ── */
        .help-grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .card-inner {
            background: #fafaf9;
            border: 1px solid #f5f5f4;
            border-radius: 12px;
            padding: 16px;
        }

        .card-inner-title {
            font-size: 13px;
            font-weight: 700;
            color: #292524;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* ── Responsive Styling ── */
        @media (max-width: 991px) {
            .help-container {
                grid-template-columns: 1fr;
            }
            .help-sidebar {
                position: relative;
                top: 0;
                width: 100%;
            }
        }

        @media (max-width: 767px) {
            .help-grid-2 {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="help-container">
        <!-- 1. Sidebar Navigation -->
        <aside class="help-sidebar">
            <h4 class="help-sidebar-title">Topik Bantuan</h4>
            <nav>
                <ul class="help-nav-list">
                    <li class="help-nav-item active" id="nav-ringkasan">
                        <a href="#ringkasan">
                            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Fungsi & Operasional
                        </a>
                    </li>
                    <li class="help-nav-item" id="nav-setup">
                        <a href="#setup">
                            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                            Konfigurasi Awal
                        </a>
                    </li>
                    <li class="help-nav-item" id="nav-bahan-baku">
                        <a href="#bahan-baku">
                            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                            Bahan Baku & Produksi
                        </a>
                    </li>
                    <li class="help-nav-item" id="nav-packing">
                        <a href="#packing">
                            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            Packing & Produk Jadi
                        </a>
                    </li>
                    <li class="help-nav-item" id="nav-penjualan">
                        <a href="#penjualan">
                            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            Penjualan Admin & Nota
                        </a>
                    </li>
                    <li class="help-nav-item" id="nav-sales">
                        <a href="#sales">
                            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1m-4 0h4m-4 0a1 1 0 01-1-1V7a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1"></path></svg>
                            Sirkulasi Sales Lapangan
                        </a>
                    </li>
                    <li class="help-nav-item" id="nav-setoran">
                        <a href="#setoran">
                            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 8h6m-5 0a3 3 0 110 6H9l3 3m-3-6h6m6 1a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Setoran & Return Sales
                        </a>
                    </li>
                    <li class="help-nav-item" id="nav-laporan">
                        <a href="#laporan">
                            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Laporan & Pengaturan
                        </a>
                    </li>
                    <li class="help-nav-item" id="nav-penting">
                        <a href="#penting">
                            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            Catatan Penting
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- 2. Main Content -->
        <main class="help-content">
            
            <!-- SECTION A: Ringkasan -->
            <section id="ringkasan" class="help-section-card">
                <div class="help-section-header">
                    <div class="help-icon-box">
                        <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="help-section-title">Ringkasan Fungsi & Operasional Harian</h3>
                </div>
                
                <p style="font-size: 13px; color: #475569; line-height: 1.6; margin-bottom: 16px;">
                    Sistem **Manajemen Kopi** dirancang untuk mendigitalisasi operasional harian terintegrasi, mulai dari pengelolaan bahan baku mentah, proses pemanggangan (produksi curah), pengemasan (packing), hingga penjualan oleh admin kasir dan sirkulasi logistik sales di lapangan.
                </p>

                <div class="help-grid-2">
                    <div class="card-inner">
                        <h5 class="card-inner-title">
                            <span style="color:#b45309;">➔</span> Alur Harian Produksi & Stok
                        </h5>
                        <p style="font-size: 12px; color: #64748b; line-height: 1.6;">
                            Bahan Baku Mentah $\rightarrow$ Produksi Curah (Grade Premium/Standar) $\rightarrow$ Packing Produk Jadi (Volume Pcs bertambah, volume curah berkurang).
                        </p>
                    </div>
                    <div class="card-inner">
                        <h5 class="card-inner-title">
                            <span style="color:#b45309;">➔</span> Alur Penjualan & Keuangan
                        </h5>
                        <p style="font-size: 12px; color: #64748b; line-height: 1.6;">
                            Transaksi Admin Kasir (Nota LX-310) ATAU Distribusi Sales (Pengajuan $\rightarrow$ Persetujuan Admin $\rightarrow$ Delivery Report ke Toko $\rightarrow$ Setoran & Return).
                        </p>
                    </div>
                </div>
            </section>

            <!-- SECTION B: Setup Awal -->
            <section id="setup" class="help-section-card">
                <div class="help-section-header">
                    <div class="help-icon-box">
                        <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                    </div>
                    <h3 class="help-section-title">Konfigurasi Penggunaan Awal (Setup)</h3>
                </div>
                
                <p style="font-size: 13px; color: #475569; line-height: 1.6; margin-bottom: 20px;">
                    Untuk memulai penggunaan pertama kali secara runut dan menghindari kesalahan integrasi, lakukan langkah persiapan data master berikut:
                </p>

                <div class="step-timeline">
                    <div class="step-node">
                        <h5 class="step-title">1. Buat Satuan & Supplier</h5>
                        <p class="step-desc">Daftarkan satuan dasar (Gram, Kilogram, Pcs) di menu **Satuan** serta daftarkan nama-nama penyedia bahan di menu **Supplier**.</p>
                    </div>
                    <div class="step-node">
                        <h5 class="step-title">2. Buat Jenis Produk (Grade Kopi)</h5>
                        <p class="step-desc">Daftarkan klasifikasi grade kopi pada menu **Kategori Produk** (contoh: <em>Kopi Premium</em>, <em>Kopi Standar</em>). Nama kategori ini akan menjadi basis pencocokan jenis curah saat packing.</p>
                    </div>
                    <div class="step-node">
                        <h5 class="step-title">3. Buat Master Bahan Baku</h5>
                        <p class="step-desc">Daftarkan bahan baku mentah (misal: Green Bean Robusta, Kemasan Alufoil) di menu **Bahan Baku** dengan mengaitkannya ke satuan dasar dan batas minimum stok.</p>
                    </div>
                    <div class="step-node">
                        <h5 class="step-title">4. Buat Master Produk Kopi (Varian Produk Jadi)</h5>
                        <p class="step-desc">Daftarkan produk siap jual di menu **Produk**. Pastikan Anda memilih **Kategori/Jenis Produk** yang tepat (Premium/Standar), serta mengisikan berat bersih (gr), satuan kemasan, dan harga jual HPP.</p>
                    </div>
                    <div class="step-node">
                        <h5 class="step-title">5. Buat Customer & User Sales</h5>
                        <p class="step-desc">Daftarkan toko/mitra di menu **Customer**. Jika ada staf sales yang akan berkeliling membawa stok, buatkan akun login ber-role <strong>Sales</strong> di menu **User**.</p>
                    </div>
                </div>
            </section>

            <!-- SECTION C: Bahan Baku & Produksi -->
            <section id="bahan-baku" class="help-section-card">
                <div class="help-section-header">
                    <div class="help-icon-box">
                        <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    </div>
                    <h3 class="help-section-title">Alur Pengelolaan Bahan Baku & Produksi Curah</h3>
                </div>

                <div class="help-grid-2">
                    <div class="card-inner">
                        <h5 class="card-inner-title" style="color: #92400e;">
                            ✦ Penerimaan Bahan Baku
                        </h5>
                        <p style="font-size: 12.5px; color: #475569; line-height: 1.6; margin-bottom: 8px;">
                            Saat membeli/menerima pasokan bahan mentah, catat transaksi di menu **Penerimaan Bahan Baku**.
                        </p>
                        <ul style="font-size: 12px; color: #64748b; padding-left: 20px; line-height: 1.5;">
                            <li>Stok fisik bahan baku di gudang otomatis bertambah.</li>
                            <li>Sistem mencatat riwayat pergerakan stok masuk secara realtime.</li>
                        </ul>
                    </div>

                    <div class="card-inner">
                        <h5 class="card-inner-title" style="color: #92400e;">
                            ✦ Proses Produksi (Roasting)
                        </h5>
                        <p style="font-size: 12.5px; color: #475569; line-height: 1.6; margin-bottom: 8px;">
                            Proses pengolahan bahan baku mentah menjadi kopi matang/roasted bean (stok curah).
                        </p>
                        <ul style="font-size: 12px; color: #64748b; padding-left: 20px; line-height: 1.5;">
                            <li>Admin memilih bahan baku yang dikonsumsi (mengurangi stok bahan baku terkait).</li>
                            <li>Menentukan **Jenis Produksi** (Premium / Standar).</li>
                            <li>Hasil output tercatat sebagai **Stok Curah** siap dikemas.</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- SECTION D: Packing -->
            <section id="packing" class="help-section-card">
                <div class="help-section-header">
                    <div class="help-icon-box">
                        <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    <h3 class="help-section-title">Alur Packing & Pengemasan Produk Jadi</h3>
                </div>
                
                <p style="font-size: 13px; color: #475569; line-height: 1.6; margin-bottom: 16px;">
                    Proses packing mengubah stok curah matang menjadi produk fisik siap jual dalam kemasan berukuran tertentu (misal: 250gr, 500gr).
                </p>

                <div class="step-timeline">
                    <div class="step-node">
                        <h5 class="step-title">Pilih Sumber Curah (Jenis Produksi)</h5>
                        <p class="step-desc">Pilih jenis curah yang akan dikemas (misal: <em>Kopi Premium</em>). Sistem secara otomatis menampilkan sisa stok curah matang yang tersedia untuk jenis tersebut.</p>
                    </div>
                    <div class="step-node">
                        <h5 class="step-title">Penyaringan Produk Hasil Packing</h5>
                        <p class="step-desc">Dropdown produk secara otomatis disaring. Anda **hanya dapat memilih produk dengan kategori yang sama persis** dengan sumber curah untuk menjaga konsistensi mutu.</p>
                    </div>
                    <div class="step-node">
                        <h5 class="step-title">Mutasi Stok Otomatis</h5>
                        <p class="step-desc">Ketika transaksi packing disimpan, **stok curah berkurang** (dihitung dalam Kg) dan **stok produk jadi bertambah** (dihitung dalam jumlah Pcs kemasan).</p>
                    </div>
                </div>

                <div class="alert-box danger">
                    <div class="alert-text">
                        <strong>Proteksi Backend Aktif:</strong> Sistem memblokir upaya penyimpanan transaksi packing secara ilegal apabila kategori produk jadi berbeda dengan jenis curah yang digunakan. Produk Premium tidak boleh dikemas ke produk berlabel Standar, dan sebaliknya.
                    </div>
                </div>
            </section>

            <!-- SECTION E: Penjualan Admin -->
            <section id="penjualan" class="help-section-card">
                <div class="help-section-header">
                    <div class="help-icon-box">
                        <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <h3 class="help-section-title">Penjualan Langsung Admin (Kasir) & Cetak Nota</h3>
                </div>
                
                <p style="font-size: 13px; color: #475569; line-height: 1.6; margin-bottom: 16px;">
                    Digunakan untuk mencatat penjualan langsung dari toko/gudang pusat kepada member atau pembeli walk-in.
                </p>

                <div class="step-timeline">
                    <div class="step-node">
                        <h5 class="step-title">Input Transaksi Penjualan</h5>
                        <p class="step-desc">Pilih produk jadi, tentukan jumlah pcs, dan sistem mengambil harga master secara otomatis. Anda dapat memasukkan data pembayaran awal secara tunai/transfer.</p>
                    </div>
                    <div class="step-node">
                        <h5 class="step-title">Pengurangan Stok Gudang</h5>
                        <p class="step-desc">Stok produk di gudang pusat (`products.current_stock`) otomatis langsung berkurang seketika setelah tombol Simpan ditekan.</p>
                    </div>
                    <div class="step-node">
                        <h5 class="step-title">Cetak Nota Fisik (Dot-Matrix LX-310)</h5>
                        <p class="step-desc">Tersedia tombol cetak khusus yang diformat monospace ringkas, siap dicetak langsung menggunakan printer kasir continuous form Epson LX-310.</p>
                    </div>
                </div>
            </section>

            <!-- SECTION F: Sales Lapangan -->
            <section id="sales" class="help-section-card">
                <div class="help-section-header">
                    <div class="help-icon-box">
                        <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1m-4 0h4m-4 0a1 1 0 01-1-1V7a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1"></path></svg>
                    </div>
                    <h3 class="help-section-title">Sirkulasi Distribusi Sales Lapangan</h3>
                </div>
                
                <p style="font-size: 13px; color: #475569; line-height: 1.6; margin-bottom: 20px;">
                    Alur logistik pengeluaran barang gudang untuk dibawa keliling oleh armada sales lapangan diatur melalui rantai persetujuan ketat:
                </p>

                <div class="step-timeline">
                    <div class="step-node">
                        <h5 class="step-title">1. Sales Membuat Pengajuan Barang</h5>
                        <p class="step-desc">Melalui aplikasi di ponsel, sales mengajukan daftar produk beserta kuantitasnya. Di tahap ini, <strong>stok gudang belum berkurang</strong> dan belum ada movement yang dicatat.</p>
                    </div>
                    <div class="step-node">
                        <h5 class="step-title">2. Admin Melakukan Approval (Persetujuan)</h5>
                        <p class="step-desc">Admin memeriksa pengajuan di panel dashboard. Setelah disetujui, sistem menggunakan penguncian baris (`lockForUpdate`) untuk memotong <strong>stok gudang pusat</strong> dan memindahkannya ke <strong>stok sales terkait</strong>.</p>
                    </div>
                    <div class="step-node">
                        <h5 class="step-title">3. Delivery Report (Laporan Pengiriman Toko)</h5>
                        <p class="step-desc">Saat sales menitipkan/menjual barang ke toko, sales membuat **Delivery Report**. Laporan ini <strong>hanya mengurangi stok bawaan mobil sales</strong> dan tidak memotong stok gudang pusat lagi.</p>
                    </div>
                </div>
            </section>

            <!-- SECTION G: Setoran & Return Sales -->
            <section id="setoran" class="help-section-card">
                <div class="help-section-header">
                    <div class="help-icon-box">
                        <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 8h6m-5 0a3 3 0 110 6H9l3 3m-3-6h6m6 1a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="help-section-title">Alur Setoran Keuangan & Return Barang Sales</h3>
                </div>

                <div class="help-grid-2">
                    <div class="card-inner">
                        <h5 class="card-inner-title" style="color: #92400e;">
                            ✦ Verifikasi Setoran Sales
                        </h5>
                        <p style="font-size: 12.5px; color: #475569; line-height: 1.6; margin-bottom: 8px;">
                            Uang tagihan yang ditagih sales dari toko disetorkan ke admin:
                        </p>
                        <ul style="font-size: 12px; color: #64748b; padding-left: 20px; line-height: 1.5; display: flex; flex-direction: column; gap: 4px;">
                            <li>Sales input nilai setoran dan wajib menyertakan foto bukti transfer (jika non-tunai).</li>
                            <li>Admin memverifikasi bukti pembayaran fisik di panel setoran.</li>
                            <li><strong>Uang masuk hanya dihitung jika status disetujui admin.</strong> Status pending/ditolak diabaikan dari total pendapatan.</li>
                        </ul>
                    </div>

                    <div class="card-inner">
                        <h5 class="card-inner-title" style="color: #92400e;">
                            ✦ Return Barang Sales
                        </h5>
                        <p style="font-size: 12.5px; color: #475569; line-height: 1.6; margin-bottom: 8px;">
                            Pengembalian produk yang tidak laku atau rusak dari toko:
                        </p>
                        <ul style="font-size: 12px; color: #64748b; padding-left: 20px; line-height: 1.5; display: flex; flex-direction: column; gap: 4px;">
                            <li>Sales input detail barang return berdasarkan nomor pengiriman.</li>
                            <li>Admin memverifikasi barang fisik dan menentukan kondisi barang.</li>
                            <li>Jika <strong>Layak Jual</strong>: Stok produk gudang pusat bertambah kembali.</li>
                            <li>Jika <strong>Perlu Proses Ulang</strong>: Stok siap jual tidak bertambah (ditangani manual untuk daur ulang).</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- SECTION H: Laporan & Pengaturan -->
            <section id="laporan" class="help-section-card">
                <div class="help-section-header">
                    <div class="help-icon-box">
                        <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <h3 class="help-section-title">Laporan Keuangan & Pengaturan Sistem</h3>
                </div>

                <div class="help-grid-2">
                    <div class="card-inner">
                        <h5 class="card-inner-title" style="color: #92400e;">
                            ✦ Dashboard & Laporan Keuangan
                        </h5>
                        <p style="font-size: 12.5px; color: #475569; line-height: 1.6; margin-bottom: 8px;">
                            Laporan dasar dan dashboard menampilkan ringkasan data finansial real:
                        </p>
                        <ul style="font-size: 12.5px; color: #64748b; padding-left: 18px; line-height: 1.5;">
                            <li><strong>Total Uang Masuk</strong>: Hanya dari setoran sales yang disetujui & pembayaran admin kasir langsung.</li>
                            <li><strong>Sisa Tagihan Toko</strong>: Nilai piutang berjalan lapangan (termasuk potongan return).</li>
                            <li><strong>Ekspor Dokumen</strong>: Mendukung download file CSV terstruktur untuk Excel serta cetak PDF arsip yang rapi.</li>
                        </ul>
                    </div>

                    <div class="card-inner">
                        <h5 class="card-inner-title" style="color: #92400e;">
                            ✦ Pengaturan (Settings)
                        </h5>
                        <p style="font-size: 12.5px; color: #475569; line-height: 1.6; margin-bottom: 8px;">
                            Kustomisasi identitas perusahaan dan templat cetak nota:
                        </p>
                        <ul style="font-size: 12.5px; color: #64748b; padding-left: 18px; line-height: 1.5;">
                            <li><strong>Identitas Usaha</strong>: Nama kedai/pabrik kopi, alamat, telepon, dan email.</li>
                            <li><strong>Nota Penjualan</strong>: Judul nota, catatan kaki (footer), dan teks tanda tangan.</li>
                            <li><strong>Kop Laporan</strong>: Judul atas dan teks nama penanggung jawab tanda tangan di PDF.</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- SECTION I: Catatan Penting -->
            <section id="penting" class="help-section-card" style="border-left: 6px solid #be123c;">
                <div class="help-section-header" style="background: #fff5f5; border-bottom: 1px solid #fee2e2; margin: -28px -28px 20px -28px; padding: 20px 28px; border-top-left-radius: 10px; border-top-right-radius: 16px;">
                    <div class="help-icon-box" style="background: white; border-color: #fee2e2; color: #be123c;">
                        <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <h3 class="help-section-title" style="color: #991b1b;">Aturan Penting & Larangan Keras Operasional</h3>
                </div>

                <div style="display: flex; flex-direction: column; gap: 14px;">
                    <div style="display: flex; gap: 10px; font-size: 13px; color: #991b1b; line-height: 1.5;">
                        <span style="font-weight: bold; flex-shrink: 0;">✕</span>
                        <span><strong>Dilarang Menggunakan Data Asal:</strong> Jangan memasukkan nilai timbangan curah atau qty packing secara sembarangan untuk data operasional riil, karena sistem mengunci kalkulasi HPP dan performa laporan keuangan secara presisi.</span>
                    </div>

                    <div style="display: flex; gap: 10px; font-size: 13px; color: #991b1b; line-height: 1.5;">
                        <span style="font-weight: bold; flex-shrink: 0;">✕</span>
                        <span><strong>Jangan Mengubah Nama Kategori yang Terpakai:</strong> Mengubah nama kategori produk (misal: "Kopi Premium") yang sudah memiliki catatan transaksi historis dapat memutus konsistensi filter dropdown pengemasan packing.</span>
                    </div>

                    <div style="display: flex; gap: 10px; font-size: 13px; color: #991b1b; line-height: 1.5;">
                        <span style="font-weight: bold; flex-shrink: 0;">✕</span>
                        <span><strong>Stok Gudang Tidak Boleh Minus:</strong> Transaksi pengeluaran barang gudang kasir maupun persetujuan barang sales akan dibatalkan otomatis oleh sistem jika kuantitas barang melebihi stok fisik yang tersedia di rak gudang.</span>
                    </div>

                    <div style="display: flex; gap: 10px; font-size: 13px; color: #991b1b; line-height: 1.5;">
                        <span style="font-weight: bold; flex-shrink: 0;">✕</span>
                        <span><strong>Delivery Report Bukan Uang Masuk:</strong> Laporan pengiriman sales ke toko hanya mencatat perpindahan barang titipan. Uang baru diakui sebagai pendapatan final gudang setelah berkas setoran sales diverifikasi dan disetujui admin.</span>
                    </div>
                </div>
            </section>

        </main>
    </div>

    <!-- Interactive Navigation Active State Switch -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const navItems = document.querySelectorAll('.help-nav-item');
            const sections = document.querySelectorAll('.help-section-card');

            // Scrollspy logic
            window.addEventListener('scroll', function() {
                let current = '';
                sections.forEach(section => {
                    const sectionTop = section.offsetTop;
                    const sectionHeight = section.clientHeight;
                    if (pageYOffset >= (sectionTop - 150)) {
                        current = section.getAttribute('id');
                    }
                });

                navItems.forEach(item => {
                    item.classList.remove('active');
                    if (item.id === `nav-${current}`) {
                        item.classList.add('active');
                    }
                });
            });

            // Smooth scroll
            navItems.forEach(item => {
                const link = item.querySelector('a');
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('href');
                    const targetSection = document.querySelector(targetId);
                    
                    window.scrollTo({
                        top: targetSection.offsetTop - 40,
                        behavior: 'smooth'
                    });
                });
            });
        });
    </script>
</x-layouts.admin>
