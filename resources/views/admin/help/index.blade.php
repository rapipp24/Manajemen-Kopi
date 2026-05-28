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
            background: #fffbfa;
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
                            Fungsi Harian
                        </a>
                    </li>
                    <li class="help-nav-item" id="nav-setup">
                        <a href="#setup">
                            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                            Langkah Awal
                        </a>
                    </li>
                    <li class="help-nav-item" id="nav-bahan-baku">
                        <a href="#bahan-baku">
                            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                            Bahan & Produksi
                        </a>
                    </li>
                    <li class="help-nav-item" id="nav-packing">
                        <a href="#packing">
                            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            Pengemasan Kopi
                        </a>
                    </li>
                    <li class="help-nav-item" id="nav-penjualan">
                        <a href="#penjualan">
                            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            Penjualan & Nota
                        </a>
                    </li>
                    <li class="help-nav-item" id="nav-sales">
                        <a href="#sales">
                            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1m-4 0h4m-4 0a1 1 0 01-1-1V7a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1"></path></svg>
                            Aktivitas Sales
                        </a>
                    </li>
                    <li class="help-nav-item" id="nav-setoran">
                        <a href="#setoran">
                            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 8h6m-5 0a3 3 0 110 6H9l3 3m-3-6h6m6 1a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Setoran & Return
                        </a>
                    </li>
                    <li class="help-nav-item" id="nav-laporan">
                        <a href="#laporan">
                            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Laporan & Sistem
                        </a>
                    </li>
                    <li class="help-nav-item" id="nav-penting">
                        <a href="#penting">
                            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            Penting Diperhatikan
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
                    Sistem Manajemen Kopi membantu mencatat alur harian usaha secara rapi dan praktis. Aplikasi ini mendokumentasikan setiap proses mulai dari penerimaan bahan baku, pengolahan kopi, pengemasan, transaksi kasir toko, hingga pemantauan kegiatan sales di lapangan.
                </p>

                <div class="help-grid-2">
                    <div class="card-inner">
                        <h5 class="card-inner-title" style="color: #78350f;">
                            Pengelolaan Stok & Produksi
                        </h5>
                        <p style="font-size: 12px; color: #64748b; line-height: 1.6;">
                            Bahan baku dicatat saat diterima, lalu dipanggang menjadi kopi curah matang, kemudian dikemas menjadi produk siap jual dalam berbagai ukuran kemasan.
                        </p>
                    </div>
                    <div class="card-inner">
                        <h5 class="card-inner-title" style="color: #78350f;">
                            Pencatatan Penjualan & Keuangan
                        </h5>
                        <p style="font-size: 12px; color: #64748b; line-height: 1.6;">
                            Penjualan langsung dicatat di kasir admin gudang pusat, sementara sirkulasi barang sales lapangan dipantau lewat pengajuan barang, laporan pengiriman toko, setoran, dan pengembalian.
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
                    <h3 class="help-section-title">Langkah Awal Persiapan Data</h3>
                </div>
                
                <p style="font-size: 13px; color: #475569; line-height: 1.6; margin-bottom: 20px;">
                    Untuk memulai penggunaan pertama kali secara teratur, lakukan pengisian data master berikut secara berurutan:
                </p>

                <div class="step-timeline">
                    <div class="step-node">
                        <h5 class="step-title">1. Buat Satuan dan Supplier</h5>
                        <p class="step-desc">Daftarkan satuan dasar (seperti Gram, Kilogram, atau Pcs) di menu Satuan, lalu daftarkan nama mitra penyedia bahan baku di menu Supplier.</p>
                    </div>
                    <div class="step-node">
                        <h5 class="step-title">2. Buat Kategori Produk (Grade Kopi)</h5>
                        <p class="step-desc">Daftarkan kelompok kualitas kopi pada menu Kategori Produk (contohnya Kopi Premium atau Kopi Standar). Kelompok ini digunakan untuk memisahkan stok curah saat pengemasan.</p>
                    </div>
                    <div class="step-node">
                        <h5 class="step-title">3. Buat Master Bahan Baku</h5>
                        <p class="step-desc">Daftarkan bahan baku mentah (seperti biji kopi mentah atau kemasan foil) di menu Bahan Baku. Tentukan satuan dan batas minimum stok aman di gudang.</p>
                    </div>
                    <div class="step-node">
                        <h5 class="step-title">4. Buat Master Produk Kopi (Varian Jadi)</h5>
                        <p class="step-desc">Daftarkan produk siap jual di menu Produk. Tentukan kelompok kopi (Premium/Standar), berat kemasan (gram), satuan, serta harga modal dan harga jual resmi.</p>
                    </div>
                    <div class="step-node">
                        <h5 class="step-title">5. Buat Customer dan Akun Sales</h5>
                        <p class="step-desc">Daftarkan toko atau mitra langganan di menu Customer. Jika memiliki staf sales lapangan, daftarkan akun mereka dengan peran Sales di menu User.</p>
                    </div>
                </div>
            </section>

            <!-- SECTION C: Bahan Baku & Produksi -->
            <section id="bahan-baku" class="help-section-card">
                <div class="help-section-header">
                    <div class="help-icon-box">
                        <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    </div>
                    <h3 class="help-section-title">Alur Pengelolaan Bahan & Produksi</h3>
                </div>

                <div class="help-grid-2">
                    <div class="card-inner">
                        <h5 class="card-inner-title" style="color: #92400e;">
                            Penerimaan Bahan Baku
                        </h5>
                        <p style="font-size: 12.5px; color: #475569; line-height: 1.6; margin-bottom: 8px;">
                            Saat menerima pasokan bahan mentah dari supplier, catat di menu Penerimaan Bahan Baku:
                        </p>
                        <ul style="font-size: 12px; color: #64748b; padding-left: 20px; line-height: 1.5; display: flex; flex-direction: column; gap: 4px;">
                            <li>Stok fisik bahan baku di gudang otomatis bertambah.</li>
                            <li>Sistem menyimpan riwayat mutasi masuk secara instan.</li>
                        </ul>
                    </div>

                    <div class="card-inner">
                        <h5 class="card-inner-title" style="color: #92400e;">
                            Proses Roasting (Produksi)
                        </h5>
                        <p style="font-size: 12.5px; color: #475569; line-height: 1.6; margin-bottom: 8px;">
                            Proses pemanggangan biji kopi mentah menjadi kopi matang siap kemas:
                        </p>
                        <ul style="font-size: 12px; color: #64748b; padding-left: 20px; line-height: 1.5; display: flex; flex-direction: column; gap: 4px;">
                            <li>Pilih bahan mentah yang digunakan untuk memotong stoknya.</li>
                            <li>Tentukan jenis hasil produksi (Kopi Premium atau Kopi Standar).</li>
                            <li>Hasil timbangan matang disimpan sebagai Kopi Curah.</li>
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
                    <h3 class="help-section-title">Alur Pengemasan (Packing) Kopi</h3>
                </div>
                
                <p style="font-size: 13px; color: #475569; line-height: 1.6; margin-bottom: 16px;">
                    Mengemas kopi curah matang menjadi kemasan kecil yang siap dijual ke pelanggan:
                </p>

                <div class="step-timeline">
                    <div class="step-node">
                        <h5 class="step-title">1. Pilih Sumber Curah</h5>
                        <p class="step-desc">Pilih jenis kopi curah yang ingin dikemas. Aplikasi otomatis menampilkan sisa stok berat kopi curah yang tersedia untuk digunakan.</p>
                    </div>
                    <div class="step-node">
                        <h5 class="step-title">2. Pilih Produk Jadi</h5>
                        <p class="step-desc">Pilih varian produk siap jual yang ingin diisi. Pilihan produk otomatis disaring hanya menampilkan produk dengan kelompok yang sama demi menjaga kualitas.</p>
                    </div>
                    <div class="step-node">
                        <h5 class="step-title">3. Penyimpanan Transaksi</h5>
                        <p class="step-desc">Saat disimpan, berat kopi curah otomatis berkurang dalam hitungan kilogram, dan jumlah kemasan produk jadi bertambah dalam hitungan pcs.</p>
                    </div>
                </div>

                <div class="alert-box">
                    <div class="alert-text">
                        <strong>Pencocokan Kualitas Kopi:</strong> Aplikasi membantu menjaga agar pilihan produk tetap sesuai dengan jenis kopi curah yang dipilih. Contohnya, kopi curah matang grade Premium hanya boleh dikemas ke dalam varian produk siap jual yang juga berkualitas Premium.
                    </div>
                </div>
            </section>

            <!-- SECTION E: Penjualan Admin -->
            <section id="penjualan" class="help-section-card">
                <div class="help-section-header">
                    <div class="help-icon-box">
                        <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <h3 class="help-section-title">Penjualan Kasir Gudang & Nota Penjualan</h3>
                </div>
                
                <p style="font-size: 13px; color: #475569; line-height: 1.6; margin-bottom: 16px;">
                    Digunakan untuk mencatat penjualan langsung dari toko utama atau kantor pusat kepada pelanggan retail atau member:
                </p>

                <div class="step-timeline">
                    <div class="step-node">
                        <h5 class="step-title">Input Transaksi Kasir</h5>
                        <p class="step-desc">Pilih produk, masukkan jumlah kemasan, dan sistem akan mengambil harga resmi secara otomatis dari data produk tanpa perlu input manual.</p>
                    </div>
                    <div class="step-node">
                        <h5 class="step-title">Pengurangan Stok Gudang</h5>
                        <p class="step-desc">Stok fisik produk di gudang pusat otomatis langsung berkurang setelah transaksi penjualan selesai disimpan.</p>
                    </div>
                    <div class="step-node">
                        <h5 class="step-title">Cetak Nota Kasir</h5>
                        <p class="step-desc">Cetak struk belanja kasir dengan menekan tombol cetak khusus, diformat rapi untuk printer kasir continuous form (Epson LX-310).</p>
                    </div>
                </div>
            </section>

            <!-- SECTION F: Sales Lapangan -->
            <section id="sales" class="help-section-card">
                <div class="help-section-header">
                    <div class="help-icon-box">
                        <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1m-4 0h4m-4 0a1 1 0 01-1-1V7a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1"></path></svg>
                    </div>
                    <h3 class="help-section-title">Aktivitas Distribusi Sales Lapangan</h3>
                </div>
                
                <p style="font-size: 13px; color: #475569; line-height: 1.6; margin-bottom: 20px;">
                    Alur pemindahan stok produk dari gudang utama untuk dibawa oleh armada mobil sales keliling diatur sebagai berikut:
                </p>

                <div class="step-timeline">
                    <div class="step-node">
                        <h5 class="step-title">1. Sales Membuat Pengajuan Barang</h5>
                        <p class="step-desc">Sales mengajukan permintaan daftar produk dan kuantitasnya lewat ponsel. Di tahap awal ini, stok gudang belum terpotong.</p>
                    </div>
                    <div class="step-node">
                        <h5 class="step-title">2. Persetujuan Admin</h5>
                        <p class="step-desc">Admin memeriksa pengajuan barang sales di panel. Setelah disetujui, stok gudang pusat akan otomatis dikurangi dan dipindahkan menjadi stok sales terkait.</p>
                    </div>
                    <div class="step-node">
                        <h5 class="step-title">3. Laporan Pengiriman (Delivery Report)</h5>
                        <p class="step-desc">Saat sales menitipkan atau menjual produk ke toko, sales membuat laporan pengiriman. Langkah ini memotong stok mobil sales, bukan stok gudang utama.</p>
                    </div>
                </div>
            </section>

            <!-- SECTION G: Setoran & Return Sales -->
            <section id="setoran" class="help-section-card">
                <div class="help-section-header">
                    <div class="help-icon-box">
                        <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 8h6m-5 0a3 3 0 110 6H9l3 3m-3-6h6m6 1a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="help-section-title">Alur Setoran Keuangan & Pengembalian Barang</h3>
                </div>

                <div class="help-grid-2">
                    <div class="card-inner">
                        <h5 class="card-inner-title" style="color: #92400e;">
                            Verifikasi Setoran Sales
                        </h5>
                        <p style="font-size: 12.5px; color: #475569; line-height: 1.6; margin-bottom: 8px;">
                            Uang pembayaran dari toko yang dikumpulkan sales wajib diserahkan kepada admin:
                        </p>
                        <ul style="font-size: 12px; color: #64748b; padding-left: 20px; line-height: 1.5; display: flex; flex-direction: column; gap: 4px;">
                            <li>Sales memasukkan nominal setoran serta menyertakan foto bukti transfer (jika non-tunai).</li>
                            <li>Admin memeriksa bukti pembayaran fisik di panel setoran.</li>
                            <li>Uang baru dihitung sebagai pendapatan usaha setelah setoran disetujui admin.</li>
                        </ul>
                    </div>

                    <div class="card-inner">
                        <h5 class="card-inner-title" style="color: #92400e;">
                            Pengembalian Barang (Return)
                        </h5>
                        <p style="font-size: 12.5px; color: #475569; line-height: 1.6; margin-bottom: 8px;">
                            Produk retur yang dikembalikan dari toko langganan ke gudang:
                        </p>
                        <ul style="font-size: 12px; color: #64748b; padding-left: 20px; line-height: 1.5; display: flex; flex-direction: column; gap: 4px;">
                            <li>Sales menginput detail produk retur berdasarkan data pengiriman.</li>
                            <li>Admin memverifikasi kondisi barang saat diterima di gudang.</li>
                            <li>Barang dengan kondisi layak jual akan otomatis dimasukkan kembali ke stok gudang pusat.</li>
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
                            Dashboard & Laporan
                        </h5>
                        <p style="font-size: 12.5px; color: #475569; line-height: 1.6; margin-bottom: 8px;">
                            Melihat perkembangan bisnis harian secara terukur:
                        </p>
                        <ul style="font-size: 12.5px; color: #64748b; padding-left: 18px; line-height: 1.5; display: flex; flex-direction: column; gap: 4px;">
                            <li>Total pendapatan usaha dihitung dari penjualan lunas kasir dan setoran sales yang disetujui.</li>
                            <li>Sisa tagihan menampilkan nilai piutang berjalan yang ada di lapangan.</li>
                            <li>Stok laporan bersifat real-time untuk memantau sisa produk saat ini secara akurat.</li>
                        </ul>
                    </div>

                    <div class="card-inner">
                        <h5 class="card-inner-title" style="color: #92400e;">
                            Pengaturan (Settings)
                        </h5>
                        <p style="font-size: 12.5px; color: #475569; line-height: 1.6; margin-bottom: 8px;">
                            Menyesuaikan identitas toko dan kop dokumen cetak:
                        </p>
                        <ul style="font-size: 12.5px; color: #64748b; padding-left: 18px; line-height: 1.5; display: flex; flex-direction: column; gap: 4px;">
                            <li>Sesuaikan nama usaha, alamat, telepon, serta logo identitas pada menu Pengaturan.</li>
                            <li>Atur catatan kaki dan teks nama tanda tangan pada struk belanja kasir.</li>
                            <li>Kelola nama penanggung jawab laporan PDF arsip kantor.</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- SECTION I: Catatan Penting -->
            <section id="penting" class="help-section-card" style="border-left: 6px solid #d97706;">
                <div class="help-section-header" style="background: #fffbeb; border-bottom: 1px solid #fef3c7; margin: -28px -28px 20px -28px; padding: 20px 28px; border-top-left-radius: 10px; border-top-right-radius: 16px;">
                    <div class="help-icon-box" style="background: white; border-color: #fde68a; color: #d97706;">
                        <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <h3 class="help-section-title" style="color: #92400e;">Hal yang Perlu Diperhatikan</h3>
                </div>

                <div style="display: flex; flex-direction: column; gap: 14px;">
                    <div style="display: flex; gap: 10px; font-size: 13px; color: #78350f; line-height: 1.5;">
                        <span style="font-weight: bold; flex-shrink: 0; color: #d97706;">✔</span>
                        <span>Gunakan data timbangan dan perhitungan yang benar saat sistem sudah mulai dipakai untuk kegiatan operasional nyata usaha.</span>
                    </div>

                    <div style="display: flex; gap: 10px; font-size: 13px; color: #78350f; line-height: 1.5;">
                        <span style="font-weight: bold; flex-shrink: 0; color: #d97706;">✔</span>
                        <span>Jangan sering mengganti nama kategori produk yang sudah pernah digunakan dalam transaksi agar pengemasan produk di menu packing tetap konsisten.</span>
                    </div>

                    <div style="display: flex; gap: 10px; font-size: 13px; color: #78350f; line-height: 1.5;">
                        <span style="font-weight: bold; flex-shrink: 0; color: #d97706;">✔</span>
                        <span>Pastikan stok fisik di rak gudang cukup sebelum menyimpan transaksi penjualan kasir atau menyetujui pengajuan barang sales.</span>
                    </div>

                    <div style="display: flex; gap: 10px; font-size: 13px; color: #78350f; line-height: 1.5;">
                        <span style="font-weight: bold; flex-shrink: 0; color: #d97706;">✔</span>
                        <span>Laporan pengiriman sales ke toko (Delivery Report) hanya mencatat sisa barang bawaan yang dititipkan, bukan penerimaan uang masuk.</span>
                    </div>

                    <div style="display: flex; gap: 10px; font-size: 13px; color: #78350f; line-height: 1.5;">
                        <span style="font-weight: bold; flex-shrink: 0; color: #d97706;">✔</span>
                        <span>Uang tagihan yang ditagih dari toko oleh sales baru akan dicatat sebagai pendapatan usaha kas masuk setelah setoran disetujui admin.</span>
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
