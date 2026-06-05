@extends('layouts.legal')

@section('title', 'Kebijakan Privasi')
@section('page_title', 'Kebijakan Privasi')
@section('page_intro', 'Kebijakan ini menjelaskan bagaimana data pengguna dan data operasional diproses dalam aplikasi Kopi Elang Emas ERM.')

@section('content')
    <h3>1. Data yang Dikumpulkan</h3>
    <p>Aplikasi dapat menyimpan nama, email, data akun, aktivitas login, pengajuan barang, laporan pengiriman, setoran, return, bukti pembayaran, dan data transaksi operasional.</p>

    <h3>2. Tujuan Penggunaan Data</h3>
    <p>Data digunakan untuk verifikasi akun, operasional sales dan admin, pelaporan, audit internal, pengelolaan stok, produksi, penjualan, dan validasi pembayaran.</p>

    <h3>3. Penyimpanan Bukti Pembayaran</h3>
    <p>File bukti pembayaran yang diunggah digunakan untuk verifikasi setoran dan dokumentasi transaksi.</p>

    <h3>4. Akses Data</h3>
    <p>Akses data dibatasi sesuai peran pengguna, seperti admin dan sales. Data tidak ditampilkan kepada pengguna yang tidak berwenang.</p>

    <h3>5. Keamanan Data</h3>
    <p>Sistem menerapkan pembatasan akses, autentikasi, pengaturan produksi, backup, dan perlindungan dasar untuk menjaga keamanan data.</p>

    <h3>6. Berbagi Data</h3>
    <p>Data tidak diperjualbelikan. Data hanya digunakan untuk kebutuhan operasional Kopi Elang Emas atau jika diwajibkan oleh ketentuan yang berlaku.</p>

    <h3>7. Backup Data</h3>
    <p>Data dapat dicadangkan secara berkala untuk kebutuhan pemulihan jika terjadi gangguan sistem.</p>

    <h3>8. Hak dan Bantuan Pengguna</h3>
    <p>Pengguna dapat menghubungi admin jika membutuhkan bantuan terkait akun atau data.</p>

    <h3>9. Kontak Admin</h3>
    <p>WhatsApp:<br>
        <strong><a href="https://wa.me/6285789741206" target="_blank" style="color: #A3470D; text-decoration: none;">+62 857-8974-1206</a></strong>
    </p>
@endsection

@section('footer_links')
    <a href="{{ route('terms') }}">Ketentuan Layanan</a>
    <span>•</span>
    <span>Kebijakan Privasi</span>
@endsection
