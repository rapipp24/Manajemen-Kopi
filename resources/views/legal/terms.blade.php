@extends('layouts.legal')

@section('title', 'Ketentuan Layanan')
@section('page_title', 'Ketentuan Layanan')
@section('page_intro', 'Ketentuan ini mengatur penggunaan aplikasi Kopi Elang Emas ERM untuk kebutuhan operasional admin dan sales.')

@section('content')
    <h3>1. Penggunaan Aplikasi</h3>
    <p>Aplikasi ini digunakan untuk mendukung operasional Kopi Elang Emas, termasuk pengelolaan akun sales, pengajuan barang, laporan pengiriman, setoran, return, stok, produksi, dan laporan internal.</p>

    <h3>2. Registrasi Akun Sales</h3>
    <p>Pengguna wajib mengisi data yang benar saat mendaftar. Akun baru wajib melakukan verifikasi email dan baru aktif setelah disetujui Admin.</p>

    <h3>3. Keamanan Akun</h3>
    <p>Pengguna bertanggung jawab menjaga kerahasiaan email, password, dan akses akunnya. Jangan membagikan akun kepada pihak lain.</p>

    <h3>4. Keakuratan Data</h3>
    <p>Pengajuan barang, laporan pengiriman, setoran, return, dan bukti pembayaran harus dicatat sesuai kondisi sebenarnya.</p>

    <h3>5. Bukti Pembayaran</h3>
    <p>Bukti pembayaran atau transfer yang diunggah harus valid dan dapat dipertanggungjawabkan.</p>

    <h3>6. Penyalahgunaan</h3>
    <p>Admin berhak menolak, membatasi, atau menonaktifkan akun jika ditemukan data tidak valid, penyalahgunaan, atau aktivitas yang merugikan operasional.</p>

    <h3>7. Perubahan Ketentuan</h3>
    <p>Ketentuan dapat diperbarui sesuai kebutuhan operasional Kopi Elang Emas.</p>

    <h3>8. Kontak Bantuan</h3>
    <p>Untuk bantuan, hubungi admin melalui WhatsApp:<br>
        <strong><a href="https://wa.me/6285789741206" target="_blank" style="color: #A3470D; text-decoration: none;">+62 857-8974-1206</a></strong>
    </p>
@endsection

@section('footer_links')
    <span>Ketentuan Layanan</span>
    <span>•</span>
    <a href="{{ route('privacy-policy') }}">Kebijakan Privasi</a>
@endsection
