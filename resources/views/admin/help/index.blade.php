<x-layouts.admin>
    <x-slot name="title">Panduan Penggunaan Sistem</x-slot>

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
            top: 80px;
            height: calc(100vh - 120px);
            background: white;
            border-radius: 16px;
            border: 1px solid #e8d8c4;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(42, 19, 13, 0.03);
            overflow-y: auto;
            scrollbar-width: thin;
        }

        .help-sidebar::-webkit-scrollbar {
            width: 4px;
        }
        .help-sidebar::-webkit-scrollbar-thumb {
            background: #d4a274;
            border-radius: 4px;
        }

        .help-sidebar-title {
            font-size: 12px;
            font-weight: 800;
            color: #723c24;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 1.5px solid #f7f0e6;
        }

        .help-nav-list {
            display: flex;
            flex-direction: column;
            gap: 4px;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .help-nav-item a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            color: #6b4c35;
            text-decoration: none;
            transition: all 0.2s;
        }

        .help-nav-item a:hover,
        .help-nav-item.active a {
            background: #fdfaf6;
            color: #92400e;
            font-weight: 600;
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
            border: 1px solid #e8d8c4;
            padding: 28px;
            box-shadow: 0 4px 12px rgba(42, 19, 13, 0.03);
            scroll-margin-top: 80px;
        }

        .help-section-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
            padding-bottom: 14px;
            border-bottom: 1px solid #f7f0e6;
        }

        .help-icon-box {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            background: #fdfaf6;
            border: 1px solid #e8d8c4;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #92400e;
            flex-shrink: 0;
        }

        .help-section-title {
            font-size: 16px;
            font-weight: 700;
            color: #2c1a0e;
            margin: 0;
        }

        .help-text {
            font-size: 13.5px;
            color: #4a2a22;
            line-height: 1.6;
            margin-bottom: 16px;
        }

        /* ── List Styles ── */
        .help-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .help-list-item {
            display: flex;
            gap: 10px;
            font-size: 13px;
            line-height: 1.5;
            color: #4a2a22;
        }

        .help-list-bullet {
            color: #b45309;
            font-weight: bold;
            flex-shrink: 0;
        }

        .help-list-text strong {
            color: #2c1a0e;
        }

        /* ── Contact Section ── */
        .help-contact-box {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #fffbeb;
            border: 1px solid #fef3c7;
            border-radius: 12px;
            padding: 20px;
            margin-top: 10px;
        }

        .help-contact-info {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .help-contact-title {
            font-size: 14px;
            font-weight: 700;
            color: #78350f;
        }

        .help-contact-desc {
            font-size: 12.5px;
            color: #92400e;
        }

        .btn-whatsapp {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #25d366;
            color: white;
            padding: 10px 18px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 13px;
            text-decoration: none;
            transition: background 0.2s;
            box-shadow: 0 4px 10px rgba(37, 211, 102, 0.2);
        }

        .btn-whatsapp:hover {
            background: #20ba5a;
        }

        /* ── Responsive Styling ── */
        @media (max-width: 1023px) {
            .help-container {
                grid-template-columns: 1fr;
            }
            .help-sidebar {
                position: relative;
                top: 0;
                height: auto;
                width: 100%;
                max-height: 200px;
            }
        }
    </style>

    <div class="help-container">
        <!-- 1. Sidebar Navigation -->
        <aside class="help-sidebar">
            <h4 class="help-sidebar-title">Daftar Panduan</h4>
            <nav>
                <ul class="help-nav-list">
                    <li class="help-nav-item active" id="nav-mulai"><a href="#mulai">1. Mulai dari Mana?</a></li>
                    <li class="help-nav-item" id="nav-akses"><a href="#akses">2. Akun & Akses</a></li>
                    <li class="help-nav-item" id="nav-master"><a href="#master">3. Data Master</a></li>
                    <li class="help-nav-item" id="nav-bahan"><a href="#bahan">4. Input Bahan Baku</a></li>
                    <li class="help-nav-item" id="nav-produksi"><a href="#produksi">5. Produksi</a></li>
                    <li class="help-nav-item" id="nav-packing"><a href="#packing">6. Packing</a></li>
                    <li class="help-nav-item" id="nav-pengajuan"><a href="#pengajuan">7. Pengajuan Sales</a></li>
                    <li class="help-nav-item" id="nav-laporan-kirim"><a href="#laporan-kirim">8. Laporan Kirim</a></li>
                    <li class="help-nav-item" id="nav-setoran"><a href="#setoran">9. Setoran</a></li>
                    <li class="help-nav-item" id="nav-return"><a href="#return">10. Return</a></li>
                    <li class="help-nav-item" id="nav-laporan-dasar"><a href="#laporan-dasar">11. Laporan Dasar</a></li>
                    <li class="help-nav-item" id="nav-export"><a href="#export">12. Export</a></li>
                    <li class="help-nav-item" id="nav-cetak"><a href="#cetak">13. Cetak LX-310</a></li>
                    <li class="help-nav-item" id="nav-backup"><a href="#backup">14. Backup & Data Aman</a></li>
                    <li class="help-nav-item" id="nav-batasan"><a href="#batasan">15. Batasan Sistem</a></li>
                </ul>
            </nav>
        </aside>

        <!-- 2. Main Content -->
        <main class="help-content">

            <!-- 1. Mulai dari Mana? -->
            <section id="mulai" class="help-section-card">
                <div class="help-section-header">
                    <div class="help-icon-box">
                        <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="help-section-title">1. Mulai dari Mana?</h3>
                </div>
                <div class="help-text">
                    Selamat datang di halaman bantuan! Berikut alur awal untuk menggunakan aplikasi ini dengan benar:
                </div>
                <ul class="help-list">
                    <li class="help-list-item">
                        <span class="help-list-bullet">1.</span>
                        <div class="help-list-text">Login sebagai <strong>Admin</strong>.</div>
                    </li>
                    <li class="help-list-item">
                        <span class="help-list-bullet">2.</span>
                        <div class="help-list-text">Isi data master terlebih dahulu. Jangan langsung membuat transaksi.</div>
                    </li>
                    <li class="help-list-item">
                        <span class="help-list-bullet">3.</span>
                        <div class="help-list-text">Mulai input secara berturut-turut: <strong>Satuan</strong>, <strong>Jenis Produk</strong>, <strong>Bahan Baku</strong>, <strong>Produk</strong>, <strong>Supplier</strong>, baru kemudian <strong>Customer/Toko</strong>.</div>
                    </li>
                    <li class="help-list-item">
                        <span class="help-list-bullet">4.</span>
                        <div class="help-list-text">Setelah data master siap, Anda bisa mulai memakai fitur <strong>Produksi</strong>, <strong>Packing</strong>, <strong>Sales Order (Pengajuan)</strong>, <strong>Laporan Kirim</strong>, <strong>Setoran</strong>, dan <strong>Return</strong>.</div>
                    </li>
                </ul>
            </section>

            <!-- 2. Akun dan Akses -->
            <section id="akses" class="help-section-card">
                <div class="help-section-header">
                    <div class="help-icon-box">
                        <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <h3 class="help-section-title">2. Akun dan Akses</h3>
                </div>
                <ul class="help-list">
                    <li class="help-list-item">
                        <span class="help-list-bullet">✔</span>
                        <div class="help-list-text"><strong>Admin</strong> bisa mengelola data master, mengontrol stok, mencatat produksi, packing, menyetujui sales order, memverifikasi setoran, mencatat return, dan melihat laporan.</div>
                    </li>
                    <li class="help-list-item">
                        <span class="help-list-bullet">✔</span>
                        <div class="help-list-text"><strong>Sales</strong> hanya bisa login ke portal khusus sales untuk membuat pengajuan barang, mengisi laporan kirim, dan menginput setoran/return.</div>
                    </li>
                    <li class="help-list-item">
                        <span class="help-list-bullet">✔</span>
                        <div class="help-list-text">Sales baru yang mendaftar wajib melakukan <strong>Verifikasi Email</strong> terlebih dahulu.</div>
                    </li>
                    <li class="help-list-item">
                        <span class="help-list-bullet">✔</span>
                        <div class="help-list-text">Setelah verifikasi email berhasil, akun sales tersebut tidak langsung aktif. Admin harus melakukan <strong>Approve</strong> di halaman Manajemen User agar sales bisa masuk ke sistem.</div>
                    </li>
                    <li class="help-list-item">
                        <span class="help-list-bullet">✔</span>
                        <div class="help-list-text">Jaga keamanan akun Anda. Jangan pernah membagikan password kepada orang lain.</div>
                    </li>
                </ul>
            </section>

            <!-- 3. Data Master -->
            <section id="master" class="help-section-card">
                <div class="help-section-header">
                    <div class="help-icon-box">
                        <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
                    </div>
                    <h3 class="help-section-title">3. Data Master</h3>
                </div>
                <ul class="help-list">
                    <li class="help-list-item">
                        <span class="help-list-bullet">✔</span>
                        <div class="help-list-text"><strong>Satuan</strong>: Buat satuan yang dipakai untuk produk dan bahan baku, seperti kg, gram, pcs, atau dus.</div>
                    </li>
                    <li class="help-list-item">
                        <span class="help-list-bullet">✔</span>
                        <div class="help-list-text"><strong>Jenis Produk</strong>: Masukkan kategori produk kopi untuk membedakan kualitas (contoh: Premium, Standar).</div>
                    </li>
                    <li class="help-list-item">
                        <span class="help-list-bullet">✔</span>
                        <div class="help-list-text"><strong>Bahan Baku</strong>: Daftarkan bahan baku dasar yang akan digunakan untuk roasting dan pengemasan.</div>
                    </li>
                    <li class="help-list-item">
                        <span class="help-list-bullet">✔</span>
                        <div class="help-list-text"><strong>Produk Kopi</strong>: Daftarkan jenis kemasan produk kopi yang siap dipacking dan dijual ke toko.</div>
                    </li>
                    <li class="help-list-item">
                        <span class="help-list-bullet">✔</span>
                        <div class="help-list-text"><strong>Supplier</strong>: Masukkan nama supplier penyedia bahan baku.</div>
                    </li>
                    <li class="help-list-item">
                        <span class="help-list-bullet">✔</span>
                        <div class="help-list-text"><strong>Customer/Toko</strong>: Daftarkan warung/toko pelanggan agar sales bisa mencatat laporan pengiriman barang ke sana.</div>
                    </li>
                    <li class="help-list-item">
                        <span class="help-list-bullet">❗</span>
                        <div class="help-list-text"><strong>Penting</strong>: Isi seluruh data master dengan teliti karena data ini akan saling terhubung di seluruh menu transaksi.</div>
                    </li>
                </ul>
            </section>

            <!-- 4. Input Bahan Baku -->
            <section id="bahan" class="help-section-card">
                <div class="help-section-header">
                    <div class="help-icon-box">
                        <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    </div>
                    <h3 class="help-section-title">4. Input Bahan Baku</h3>
                </div>
                <div class="help-text">
                    Cara mencatat dan mengelola bahan mentah:
                </div>
                <ul class="help-list">
                    <li class="help-list-item">
                        <span class="help-list-bullet">1.</span>
                        <div class="help-list-text">Buka menu <strong>Bahan Baku</strong>.</div>
                    </li>
                    <li class="help-list-item">
                        <span class="help-list-bullet">2.</span>
                        <div class="help-list-text">Klik tambah bahan baku. Isi nama bahan baku, pilih satuan, dan tentukan jumlah stok minimum aman.</div>
                    </li>
                    <li class="help-list-item">
                        <span class="help-list-bullet">3.</span>
                        <div class="help-list-text">Jika ada kiriman baru dari supplier, gunakan menu <strong>Penerimaan Bahan Baku</strong> untuk mencatat agar stok ter-update otomatis.</div>
                    </li>
                    <li class="help-list-item">
                        <span class="help-list-bullet">4.</span>
                        <div class="help-list-text"><strong>Hindari</strong> mengubah angka stok secara manual di master data jika transaksi harian sudah mulai berjalan.</div>
                    </li>
                </ul>
            </section>

            <!-- 5. Produksi -->
            <section id="produksi" class="help-section-card">
                <div class="help-section-header">
                    <div class="help-icon-box">
                        <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                    </div>
                    <h3 class="help-section-title">5. Produksi</h3>
                </div>
                <div class="help-text">
                    Menu ini dipakai saat proses menyangrai/mengolah kopi mentah menjadi kopi matang curah:
                </div>
                <ul class="help-list">
                    <li class="help-list-item">
                        <span class="help-list-bullet">1.</span>
                        <div class="help-list-text">Buka menu <strong>Produksi</strong>, lalu buat produksi baru.</div>
                    </li>
                    <li class="help-list-item">
                        <span class="help-list-bullet">2.</span>
                        <div class="help-list-text">Pilih bahan baku mentah yang akan diolah (stok bahan baku mentah akan otomatis terpotong).</div>
                    </li>
                    <li class="help-list-item">
                        <span class="help-list-bullet">3.</span>
                        <div class="help-list-text">Isi jumlah berat bahan mentah yang dipakai, lalu masukkan berat hasil kopi matang yang diperoleh.</div>
                    </li>
                    <li class="help-list-item">
                        <span class="help-list-bullet">4.</span>
                        <div class="help-list-text">Cek kembali seluruh angka sebelum menyimpannya. Kesalahan input akan membuat kalkulasi sisa stok menjadi tidak pas.</div>
                    </li>
                </ul>
            </section>

            <!-- 6. Packing -->
            <section id="packing" class="help-section-card">
                <div class="help-section-header">
                    <div class="help-icon-box">
                        <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    </div>
                    <h3 class="help-section-title">6. Packing</h3>
                </div>
                <div class="help-text">
                    Lakukan langkah berikut saat mengemas kopi curah matang menjadi bungkusan siap jual:
                </div>
                <ul class="help-list">
                    <li class="help-list-item">
                        <span class="help-list-bullet">1.</span>
                        <div class="help-list-text">Pilih data produksi kopi curah matang yang akan dikemas.</div>
                    </li>
                    <li class="help-list-item">
                        <span class="help-list-bullet">2.</span>
                        <div class="help-list-text">Pilih varian produk siap jual yang ingin Anda isi.</div>
                    </li>
                    <li class="help-list-item">
                        <span class="help-list-bullet">3.</span>
                        <div class="help-list-text">Masukkan jumlah bungkus (pcs) yang berhasil dikemas secara teliti.</div>
                    </li>
                    <li class="help-list-item">
                        <span class="help-list-bullet">4.</span>
                        <div class="help-list-text">Pastikan stok hasil packing sudah sesuai dengan kondisi barang fisik di rak penyimpanan.</div>
                    </li>
                </ul>
            </section>

            <!-- 7. Sales Order / Pengajuan Sales -->
            <section id="pengajuan" class="help-section-card">
                <div class="help-section-header">
                    <div class="help-icon-box">
                        <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    </div>
                    <h3 class="help-section-title">7. Sales Order / Pengajuan Sales</h3>
                </div>
                <div class="help-text">
                    Alur pendistribusian stok barang untuk dibawa sales keliling:
                </div>
                <ul class="help-list">
                    <li class="help-list-item">
                        <span class="help-list-bullet">1.</span>
                        <div class="help-list-text">Sales membuat pengajuan daftar barang bawaan dari ponsel mereka.</div>
                    </li>
                    <li class="help-list-item">
                        <span class="help-list-bullet">2.</span>
                        <div class="help-list-text">Buka menu <strong>Sales Order</strong> di dashboard admin. Cek daftar barang yang diminta sales.</div>
                    </li>
                    <li class="help-list-item">
                        <span class="help-list-bullet">3.</span>
                        <div class="help-list-text">Jika stok di gudang tersedia dan jumlahnya wajar, klik tombol <strong>Approve</strong> (Setujui).</div>
                    </li>
                    <li class="help-list-item">
                        <span class="help-list-bullet">4.</span>
                        <div class="help-list-text">Setelah disetujui, stok gudang pusat otomatis berkurang dan berpindah menjadi stok muatan sales tersebut.</div>
                    </li>
                </ul>
            </section>

            <!-- 8. Laporan Kirim -->
            <section id="laporan-kirim" class="help-section-card">
                <div class="help-section-header">
                    <div class="help-icon-box">
                        <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1m-4 0h4m-4 0a1 1 0 01-1-1V7a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1"></path></svg>
                    </div>
                    <h3 class="help-section-title">8. Laporan Kirim</h3>
                </div>
                <ul class="help-list">
                    <li class="help-list-item">
                        <span class="help-list-bullet">✔</span>
                        <div class="help-list-text">Sales menginput laporan setiap kali menitipkan atau menjual produk ke toko mitra.</div>
                    </li>
                    <li class="help-list-item">
                        <span class="help-list-bullet">✔</span>
                        <div class="help-list-text">Sales harus memilih toko/customer yang dituju terlebih dulu.</div>
                    </li>
                    <li class="help-list-item">
                        <span class="help-list-bullet">✔</span>
                        <div class="help-list-text">Sales menginput kuantitas produk yang dikirimkan.</div>
                    </li>
                    <li class="help-list-item">
                        <span class="help-list-bullet">✔</span>
                        <div class="help-list-text">Pastikan kuantitas yang di-input sales sudah sesuai dengan jumlah fisik produk yang diserahkan ke toko.</div>
                    </li>
                </ul>
            </section>

            <!-- 9. Setoran -->
            <section id="setoran" class="help-section-card">
                <div class="help-section-header">
                    <div class="help-icon-box">
                        <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="help-section-title">9. Setoran</h3>
                </div>
                <div class="help-text">
                    Verifikasi setoran uang hasil penjualan sales di lapangan:
                </div>
                <ul class="help-list">
                    <li class="help-list-item">
                        <span class="help-list-bullet">1.</span>
                        <div class="help-list-text">Sales menyetor uang tagihan dan menginput detail nominalnya ke sistem.</div>
                    </li>
                    <li class="help-list-item">
                        <span class="help-list-bullet">2.</span>
                        <div class="help-list-text">Jika disetor via transfer bank, sales harus mengunggah foto bukti transfer.</div>
                    </li>
                    <li class="help-list-item">
                        <span class="help-list-bullet">3.</span>
                        <div class="help-list-text">Admin mencocokkan nominal setoran dengan bukti fisik atau saldo rekening masuk.</div>
                    </li>
                    <li class="help-list-item">
                        <span class="help-list-bullet">4.</span>
                        <div class="help-list-text">Jika sudah cocok, admin klik <strong>Approve</strong> agar uang tercatat resmi masuk ke keuangan sistem.</div>
                    </li>
                </ul>
            </section>

            <!-- 10. Return -->
            <section id="return" class="help-section-card">
                <div class="help-section-header">
                    <div class="help-icon-box">
                        <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 15v-6a4 4 0 00-4-4H4m0 0l4-4m-4 4l4 4m-4 5h16a4 4 0 014 4v2"></path></svg>
                    </div>
                    <h3 class="help-section-title">10. Return</h3>
                </div>
                <div class="help-text">
                    Alur penerimaan barang pengembalian dari toko:
                </div>
                <ul class="help-list">
                    <li class="help-list-item">
                        <span class="help-list-bullet">1.</span>
                        <div class="help-list-text">Return dibuat jika ada barang tidak laku atau rusak yang ditarik dari toko.</div>
                    </li>
                    <li class="help-list-item">
                        <span class="help-list-bullet">2.</span>
                        <div class="help-list-text">Sales menginput nama produk, kuantitas, serta alasan pengembalian.</div>
                    </li>
                    <li class="help-list-item">
                        <span class="help-list-bullet">3.</span>
                        <div class="help-list-text">Admin wajib memeriksa kondisi fisik produk ketika diserahkan sales kembali ke gudang pusat.</div>
                    </li>
                    <li class="help-list-item">
                        <span class="help-list-bullet">4.</span>
                        <div class="help-list-text">Jika disetujui admin, stok produk tersebut akan masuk kembali ke inventaris gudang pusat.</div>
                    </li>
                </ul>
            </section>

            <!-- 11. Laporan Dasar -->
            <section id="laporan-dasar" class="help-section-card">
                <div class="help-section-header">
                    <div class="help-icon-box">
                        <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                    <h3 class="help-section-title">11. Laporan Dasar</h3>
                </div>
                <ul class="help-list">
                    <li class="help-list-item">
                        <span class="help-list-bullet">✔</span>
                        <div class="help-list-text">Gunakan menu Laporan Dasar untuk memantau sisa stok barang, total penjualan kasir, total setoran sales, hasil produksi, dan aktivitas sales di lapangan.</div>
                    </li>
                    <li class="help-list-item">
                        <span class="help-list-bullet">✔</span>
                        <div class="help-list-text">Atur <strong>Filter Tanggal</strong> di atas tabel untuk melihat aktivitas pada periode hari atau bulan tertentu.</div>
                    </li>
                    <li class="help-list-item">
                        <span class="help-list-bullet">✔</span>
                        <div class="help-list-text">Pastikan seluruh data transaksi harian di-input dengan benar agar angka laporan keuangan tetap akurat.</div>
                    </li>
                </ul>
            </section>

            <!-- 12. Export -->
            <section id="export" class="help-section-card">
                <div class="help-section-header">
                    <div class="help-icon-box">
                        <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    </div>
                    <h3 class="help-section-title">12. Export</h3>
                </div>
                <ul class="help-list">
                    <li class="help-list-item">
                        <span class="help-list-bullet">✔</span>
                        <div class="help-list-text">Gunakan fitur export jika Anda ingin mengunduh laporan ke file luar (seperti Excel atau PDF) untuk diarsipkan.</div>
                    </li>
                    <li class="help-list-item">
                        <span class="help-list-bullet">✔</span>
                        <div class="help-list-text">Periksa kembali filter rentang tanggal sebelum mengunduh agar data yang ter-export tidak salah.</div>
                    </li>
                    <li class="help-list-item">
                        <span class="help-list-bullet">✔</span>
                        <div class="help-list-text">Simpan file hasil export laporan di folder komputer yang aman.</div>
                    </li>
                </ul>
            </section>

            <!-- 13. Cetak LX-310 -->
            <section id="cetak" class="help-section-card">
                <div class="help-section-header">
                    <div class="help-icon-box">
                        <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9v6z"></path></svg>
                    </div>
                    <h3 class="help-section-title">13. Cetak LX-310</h3>
                </div>
                <ul class="help-list">
                    <li class="help-list-item">
                        <span class="help-list-bullet">✔</span>
                        <div class="help-list-text">Gunakan printer **Epson LX-310** untuk mencetak nota penjualan fisik atau laporan yang sudah disediakan.</div>
                    </li>
                    <li class="help-list-item">
                        <span class="help-list-bullet">✔</span>
                        <div class="help-list-text">Pastikan printer sudah dinyalakan, kabel terhubung, dan kertas continuous form sudah terpasang dengan pas di traktor printer.</div>
                    </li>
                    <li class="help-list-item">
                        <span class="help-list-bullet">✔</span>
                        <div class="help-list-text">Saat jendela print dari browser muncul, periksa halaman pratinjau (preview) terlebih dahulu untuk memastikan margin dan ukuran kertas pas.</div>
                    </li>
                    <li class="help-list-item">
                        <span class="help-list-bullet">✔</span>
                        <div class="help-list-text">Jangan mencetak nota sebelum data transaksi kasir dipastikan benar agar tidak membuang kertas.</div>
                    </li>
                </ul>
            </section>

            <!-- 14. Backup dan Data Aman -->
            <section id="backup" class="help-section-card">
                <div class="help-section-header">
                    <div class="help-icon-box">
                        <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                    </div>
                    <h3 class="help-section-title">14. Backup dan Data Aman</h3>
                </div>
                <ul class="help-list">
                    <li class="help-list-item">
                        <span class="help-list-bullet">✔</span>
                        <div class="help-list-text">Proses backup database dan unggahan file foto berjalan secara otomatis di server background.</div>
                    </li>
                    <li class="help-list-item">
                        <span class="help-list-bullet">✔</span>
                        <div class="help-list-text">Meski otomatis, admin disarankan mengecek hasil backup berkala untuk memastikan file tersimpan dengan aman.</div>
                    </li>
                    <li class="help-list-item">
                        <span class="help-list-bullet">✔</span>
                        <div class="help-list-text">Jangan mengisi data sistem utama (production) dengan data palsu atau coba-coba agar pembukuan tidak kacau.</div>
                    </li>
                    <li class="help-list-item">
                        <span class="help-list-bullet">✔</span>
                        <div class="help-list-text">Jika terjadi error atau kesalahan besar pada data utama, sistem bisa dipulihkan kembali dari file backup paling akhir.</div>
                    </li>
                </ul>
            </section>

            <!-- 15. Batasan Sistem -->
            <section id="batasan" class="help-section-card" style="border-left: 4px solid #ef4444;">
                <div class="help-section-header">
                    <div class="help-icon-box" style="color: #ef4444; background: #fef2f2; border-color: #fee2e2;">
                        <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <h3 class="help-section-title" style="color: #991b1b;">15. Batasan Sistem</h3>
                </div>
                <ul class="help-list">
                    <li class="help-list-item">
                        <span class="help-list-bullet">⚠️</span>
                        <div class="help-list-text">Akun sales baru wajib memverifikasi email mereka sendiri.</div>
                    </li>
                    <li class="help-list-item">
                        <span class="help-list-bullet">⚠️</span>
                        <div class="help-list-text">Akun sales baru wajib di-approve admin secara manual sebelum bisa login.</div>
                    </li>
                    <li class="help-list-item">
                        <span class="help-list-bullet">⚠️</span>
                        <div class="help-list-text">Jumlah stok dalam sistem bergantung pada input transaksi Anda. Jika input salah, stok di gudang dan laporan juga ikut salah.</div>
                    </li>
                    <li class="help-list-item">
                        <span class="help-list-bullet">⚠️</span>
                        <div class="help-list-text">Mengubah data master yang sudah lama digunakan dalam transaksi dapat berdampak pada riwayat pencatatan lama.</div>
                    </li>
                    <li class="help-list-item">
                        <span class="help-list-bullet">⚠️</span>
                        <div class="help-list-text">Sistem production ini adalah sistem nyata, jangan menginput data transaksi palsu untuk coba-coba.</div>
                    </li>
                </ul>

                <!-- WhatsApp Help Button -->
                <div class="help-contact-box">
                    <div class="help-contact-info">
                        <span class="help-contact-title">Ada Pertanyaan atau Masalah Sistem?</span>
                        <span class="help-contact-desc">Hubungi tim admin atau support kami langsung melalui WhatsApp.</span>
                    </div>
                    <a href="https://wa.me/6285789741206" target="_blank" class="btn-whatsapp">
                        <svg style="width: 16px; height: 16px; fill: currentColor;" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.559 5.338-11.894 11.897-11.894 3.178.002 6.165 1.237 8.407 3.483 2.243 2.245 3.475 5.234 3.473 8.411-.004 6.559-5.338 11.895-11.893 11.895-2.007-.001-3.982-.507-5.748-1.472l-6.237 1.636zm6.345-3.66c1.656.983 3.279 1.498 4.97 1.5c5.586.002 10.126-4.537 10.128-10.124.001-2.707-1.05-5.253-2.96-7.164-1.91-1.912-4.455-2.964-7.169-2.965-5.585 0-10.126 4.537-10.129 10.128-.002 1.782.477 3.518 1.4 5.092l-.991 3.619 3.73-.977zm11.391-7.135c-.29-.145-1.716-.847-1.982-.944-.266-.097-.461-.145-.656.145-.194.29-.754.944-.925 1.137-.172.194-.343.218-.633.073-.29-.145-1.226-.452-2.335-1.441-.864-.771-1.447-1.723-1.617-2.013-.17-.29-.018-.447.127-.591.131-.13.29-.34.436-.51.145-.17.194-.291.291-.485.097-.194.049-.364-.025-.51-.073-.145-.656-1.58-.9-2.164-.236-.57-.477-.492-.656-.501-.17-.008-.364-.01-.559-.01-.195 0-.51.073-.777.364-.266.291-1.02 1.002-1.02 2.446 0 1.445 1.049 2.844 1.196 3.037.147.194 2.064 3.153 5.001 4.428.699.303 1.244.485 1.67.62.703.223 1.344.192 1.85.117.564-.084 1.716-.701 1.958-1.378.242-.676.242-1.258.17-1.378-.072-.12-.266-.218-.557-.363z"/></svg>
                        Hubungi via WhatsApp
                    </a>
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
                    if (pageYOffset >= (sectionTop - 120)) {
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
                        top: targetSection.offsetTop - 80,
                        behavior: 'smooth'
                    });
                });
            });
        });
    </script>
</x-layouts.admin>
