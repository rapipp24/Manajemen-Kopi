<x-layouts.user>
    <x-slot name="title">Panduan Sales</x-slot>

    <style>
        /* ── Theme & Layout ── */
        .help-container {
            max-width: 900px;
            margin: 0 auto 50px auto;
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .help-header-card {
            background: linear-gradient(135deg, #2a170e 0%, #1c0d02 100%);
            color: #f5efe6;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(42, 23, 14, 0.05);
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .help-header-title {
            font-size: 20px;
            font-weight: 800;
            color: #ffffff;
        }

        .help-header-desc {
            font-size: 13.5px;
            color: #eae3d2;
            line-height: 1.5;
        }

        .help-section-card {
            background: white;
            border: 1px solid #eae3d2;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 12px rgba(42, 23, 14, 0.02);
        }

        .help-section-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 1px solid #fbf9f4;
        }

        .help-icon-box {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: #f5efe6;
            border: 1px solid #eae3d2;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #2a170e;
            font-weight: bold;
            flex-shrink: 0;
        }

        .help-section-title {
            font-size: 15px;
            font-weight: 700;
            color: #2a170e;
            margin: 0;
        }

        .help-text {
            font-size: 13px;
            color: #705f56;
            line-height: 1.6;
            margin-bottom: 12px;
        }

        /* ── List Styles ── */
        .help-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .help-list-item {
            display: flex;
            gap: 8px;
            font-size: 13px;
            line-height: 1.5;
            color: #2a170e;
        }

        .help-list-bullet {
            color: #c5a059;
            font-weight: bold;
            flex-shrink: 0;
        }

        .help-list-text strong {
            color: #1c0d02;
        }

        /* ── Alert Box ── */
        .help-alert-box {
            background: #fffdf5;
            border: 1px solid #fecaca;
            border-radius: 12px;
            padding: 16px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 10px;
        }

        .help-alert-item {
            display: flex;
            gap: 8px;
            font-size: 12.5px;
            line-height: 1.5;
            color: #991b1b;
        }

        .help-alert-bullet {
            font-weight: bold;
            flex-shrink: 0;
        }

        /* ── Contact Section ── */
        .help-contact-box {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #fffdf5;
            border: 1px solid #eae3d2;
            border-radius: 12px;
            padding: 18px;
            margin-top: 10px;
        }

        .help-contact-info {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .help-contact-title {
            font-size: 13.5px;
            font-weight: 700;
            color: #2a170e;
        }

        .help-contact-desc {
            font-size: 12px;
            color: #705f56;
        }

        .btn-whatsapp {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #25d366;
            color: white;
            padding: 8px 14px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 12px;
            text-decoration: none;
            transition: background 0.2s;
            box-shadow: 0 4px 8px rgba(37, 211, 102, 0.15);
        }

        .btn-whatsapp:hover {
            background: #20ba5a;
        }

        @media (max-width: 600px) {
            .help-contact-box {
                flex-direction: column;
                align-items: stretch;
                gap: 14px;
                text-align: center;
            }
            .btn-whatsapp {
                justify-content: center;
            }
        }
    </style>

    <div class="help-container">
        <!-- Header Card -->
        <div class="help-header-card">
            <h1 class="help-header-title">Pusat Panduan & Bantuan Sales</h1>
            <p class="help-header-desc">
                Halaman ini berisi petunjuk singkat cara memakai portal sales. Ikuti panduan langkah demi langkah di bawah ini untuk mencatat aktivitas harian Anda dengan lancar.
            </p>
        </div>

        <!-- 1. Login dan Akses Sales -->
        <section class="help-section-card">
            <div class="help-section-header">
                <div class="help-icon-box">1</div>
                <h3 class="help-section-title">Login dan Akses Sales</h3>
            </div>
            <ul class="help-list">
                <li class="help-list-item">
                    <span class="help-list-bullet">➔</span>
                    <div class="help-list-text">Masuk menggunakan email dan password terdaftar di halaman <strong>Login</strong>.</div>
                </li>
                <li class="help-list-item">
                    <span class="help-list-bullet">➔</span>
                    <div class="help-list-text">Jika belum punya akun, silakan klik daftar baru lewat halaman <strong>Register</strong>.</div>
                </li>
                <li class="help-list-item">
                    <span class="help-list-bullet">➔</span>
                    <div class="help-list-text">Setelah mendaftar, buka kotak masuk email Anda dan klik link verifikasi yang dikirimkan.</div>
                </li>
                <li class="help-list-item">
                    <span class="help-list-bullet">➔</span>
                    <div class="help-list-text">Setelah verifikasi email selesai, Anda harus menunggu admin meng-approve akun Anda agar bisa masuk ke menu portal sales.</div>
                </li>
            </ul>
        </section>

        <!-- 2. Pengajuan Barang -->
        <section class="help-section-card">
            <div class="help-section-header">
                <div class="help-icon-box">2</div>
                <h3 class="help-section-title">Pengajuan Barang Bawaan</h3>
            </div>
            <ul class="help-list">
                <li class="help-list-item">
                    <span class="help-list-bullet">➔</span>
                    <div class="help-list-text">Buka menu <strong>Buat Pengajuan</strong> sebelum berangkat ke lapangan.</div>
                </li>
                <li class="help-list-item">
                    <span class="help-list-bullet">➔</span>
                    <div class="help-list-text">Pilih produk kopi yang akan dibawa, lalu ketik jumlah kemasan yang Anda perlukan.</div>
                </li>
                <li class="help-list-item">
                    <span class="help-list-bullet">➔</span>
                    <div class="help-list-text">Klik kirim pengajuan barang.</div>
                </li>
                <li class="help-list-item">
                    <span class="help-list-bullet">➔</span>
                    <div class="help-list-text">Tunggu admin memeriksa dan melakukan approve. Setelah di-approve, stok barang otomatis masuk ke muatan sales Anda.</div>
                </li>
            </ul>
        </section>

        <!-- 3. Laporan Kirim -->
        <section class="help-section-card">
            <div class="help-section-header">
                <div class="help-icon-box">3</div>
                <h3 class="help-section-title">Laporan Kirim (Titip/Jual ke Toko)</h3>
            </div>
            <ul class="help-list">
                <li class="help-list-item">
                    <span class="help-list-bullet">➔</span>
                    <div class="help-list-text">Buat laporan pengiriman sesaat setelah Anda menyerahkan barang ke toko/customer.</div>
                </li>
                <li class="help-list-item">
                    <span class="help-list-bullet">➔</span>
                    <div class="help-list-text">Pilih toko/customer tujuan pengiriman.</div>
                </li>
                <li class="help-list-item">
                    <span class="help-list-bullet">➔</span>
                    <div class="help-list-text">Pilih produk yang diserahkan dan isi jumlah kuantitasnya sesuai barang fisik yang benar-benar Anda turunkan dari mobil.</div>
                </li>
                <li class="help-list-item">
                    <span class="help-list-bullet">➔</span>
                    <div class="help-list-text">Periksa ulang daftar produk dan jumlahnya agar tidak salah sebelum menekan tombol simpan.</div>
                </li>
            </ul>
        </section>

        <!-- 4. Setoran -->
        <section class="help-section-card">
            <div class="help-section-header">
                <div class="help-icon-box">4</div>
                <h3 class="help-section-title">Setoran Keuangan</h3>
            </div>
            <ul class="help-list">
                <li class="help-list-item">
                    <span class="help-list-bullet">➔</span>
                    <div class="help-list-text">Segera buat laporan setoran setelah Anda menerima pembayaran tagihan dari toko.</div>
                </li>
                <li class="help-list-item">
                    <span class="help-list-bullet">➔</span>
                    <div class="help-list-text">Masukkan jumlah uang yang Anda setorkan ke sistem.</div>
                </li>
                <li class="help-list-item">
                    <span class="help-list-bullet">➔</span>
                    <div class="help-list-text">Jika setoran dilakukan lewat transfer bank, lampirkan foto bukti transfer yang asli dan jelas.</div>
                </li>
                <li class="help-list-item">
                    <span class="help-list-bullet">➔</span>
                    <div class="help-list-text">Tunggu admin memverifikasi dan menyetujui setoran Anda agar transaksi dianggap selesai.</div>
                </li>
            </ul>
        </section>

        <!-- 5. Return -->
        <section class="help-section-card">
            <div class="help-section-header">
                <div class="help-icon-box">5</div>
                <h3 class="help-section-title">Return Barang</h3>
            </div>
            <ul class="help-list">
                <li class="help-list-item">
                    <span class="help-list-bullet">➔</span>
                    <div class="help-list-text">Gunakan menu ini jika ada barang sisa atau rusak yang ditarik/dikembalikan dari toko.</div>
                </li>
                <li class="help-list-item">
                    <span class="help-list-bullet">➔</span>
                    <div class="help-list-text">Pilih produk kopi yang dikembalikan, ketik jumlahnya, serta berikan alasan return.</div>
                </li>
                <li class="help-list-item">
                    <span class="help-list-bullet">➔</span>
                    <div class="help-list-text">Pastikan data yang Anda input sudah sesuai dengan kondisi barang retur asli yang dibawa.</div>
                </li>
            </ul>
        </section>

        <!-- 6. Riwayat -->
        <section class="help-section-card">
            <div class="help-section-header">
                <div class="help-icon-box">6</div>
                <h3 class="help-section-title">Melihat Riwayat</h3>
            </div>
            <ul class="help-list">
                <li class="help-list-item">
                    <span class="help-list-bullet">➔</span>
                    <div class="help-list-text">Gunakan menu Riwayat untuk mengecek ulang daftar pengajuan barang, laporan pengiriman toko, setoran uang, serta return Anda.</div>
                </li>
                <li class="help-list-item">
                    <span class="help-list-bullet">➔</span>
                    <div class="help-list-text">Jika Anda menemukan ada kesalahan data atau keliru input, segera hubungi admin untuk melakukan perbaikan.</div>
                </li>
            </ul>
        </section>

        <!-- 7. Batasan & Catatan Penting -->
        <section class="help-section-card" style="border-left: 5px solid #ef4444;">
            <div class="help-section-header">
                <div class="help-icon-box" style="color: #ef4444; background: #fff5f5; border-color: #fee2e2;">⚠️</div>
                <h3 class="help-section-title" style="color: #991b1b;">Penting untuk Diperhatikan</h3>
            </div>
            <div class="help-alert-box">
                <div class="help-alert-item">
                    <span class="help-alert-bullet">•</span>
                    <div class="help-alert-text">Jumlah stok dalam sistem berjalan otomatis mengikuti input Anda. Jika input Anda keliru, stok muatan mobil Anda dan laporan penjualan akan menjadi kacau.</div>
                </div>
                <div class="help-alert-item">
                    <span class="help-alert-bullet">•</span>
                    <div class="help-alert-text">Jangan mengisi data transaksi asal-asalan. Seluruh data transaksi dipantau langsung oleh admin.</div>
                </div>
                <div class="help-alert-item">
                    <span class="help-alert-bullet">•</span>
                    <div class="help-alert-text">Pastikan foto bukti transfer transfer bank yang Anda upload benar dan terbaca jelas sebelum disimpan.</div>
                </div>
                <div class="help-alert-item">
                    <span class="help-alert-bullet">•</span>
                    <div class="help-alert-text">Jika email verifikasi saat daftar baru tidak masuk, coba cek folder spam di email Anda, atau hubungi admin gudang.</div>
                </div>
                <div class="help-alert-item">
                    <span class="help-alert-bullet">•</span>
                    <div class="help-alert-text">Jika Anda lupa kata sandi akun, silakan gunakan menu Lupa Password di halaman login utama.</div>
                </div>
            </div>
        </section>

        <!-- 8. Bantuan WhatsApp -->
        <section class="help-section-card" id="bantuan">
            <div class="help-section-header">
                <div class="help-icon-box">?</div>
                <h3 class="help-section-title">Hubungi Layanan Bantuan</h3>
            </div>
            <div class="help-text">
                Jika Anda menemui kendala dalam melakukan login, masalah verifikasi email, kesalahan input stok, atau kebingungan transaksi, segera hubungi admin melalui kontak WhatsApp di bawah ini.
            </div>
            <div class="help-contact-box">
                <div class="help-contact-info">
                    <span class="help-contact-title">Butuh bantuan langsung?</span>
                    <span class="help-contact-desc">Tanyakan kepada admin sistem jika ada kendala teknis lapangan.</span>
                </div>
                <a href="https://wa.me/6285789741206" target="_blank" class="btn-whatsapp">
                    <svg style="width: 14px; height: 14px; fill: currentColor; transform: translateY(1px);" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.559 5.338-11.894 11.897-11.894 3.178.002 6.165 1.237 8.407 3.483 2.243 2.245 3.475 5.234 3.473 8.411-.004 6.559-5.338 11.895-11.893 11.895-2.007-.001-3.982-.507-5.748-1.472l-6.237 1.636zm6.345-3.66c1.656.983 3.279 1.498 4.97 1.5c5.586.002 10.126-4.537 10.128-10.124.001-2.707-1.05-5.253-2.96-7.164-1.91-1.912-4.455-2.964-7.169-2.965-5.585 0-10.126 4.537-10.129 10.128-.002 1.782.477 3.518 1.4 5.092l-.991 3.619 3.73-.977zm11.391-7.135c-.29-.145-1.716-.847-1.982-.944-.266-.097-.461-.145-.656.145-.194.29-.754.944-.925 1.137-.172.194-.343.218-.633.073-.29-.145-1.226-.452-2.335-1.441-.864-.771-1.447-1.723-1.617-2.013-.17-.29-.018-.447.127-.591.131-.13.29-.34.436-.51.145-.17.194-.291.291-.485.097-.194.049-.364-.025-.51-.073-.145-.656-1.58-.9-2.164-.236-.57-.477-.492-.656-.501-.17-.008-.364-.01-.559-.01-.195 0-.51.073-.777.364-.266.291-1.02 1.002-1.02 2.446 0 1.445 1.049 2.844 1.196 3.037.147.194 2.064 3.153 5.001 4.428.699.303 1.244.485 1.67.62.703.223 1.344.192 1.85.117.564-.084 1.716-.701 1.958-1.378.242-.676.242-1.258.17-1.378-.072-.12-.266-.218-.557-.363z"/></svg>
                    Hubungi via WhatsApp
                </a>
            </div>
        </section>
    </div>
</x-layouts.user>
