<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Tampilkan halaman pengaturan
     */
    public function index()
    {
        $settings = [
            // 1. Identitas Usaha
            'shop_name'                 => Setting::get('shop_name', 'MANAJEMEN KOPI'),
            'shop_tagline'              => Setting::get('shop_tagline', 'Panel Manajemen'),
            'shop_address'              => Setting::get('shop_address', 'Jl. Kopi Nikmat No. 123, Indonesia'),
            'shop_phone'                => Setting::get('shop_phone', '(021) 1234-5678'),
            'shop_email'                => Setting::get('shop_email', 'hello@kopimanajer.com'),

            // 2. Pengaturan Nota Penjualan
            'receipt_title'                 => Setting::get('receipt_title', 'INVOICE PENJUALAN'),
            'footer_note'                   => Setting::get('footer_note', 'Barang yang sudah dibeli tidak dapat ditukar atau dikembalikan kecuali ada perjanjian sebelumnya.'),
            'receipt_thank_you_text'        => Setting::get('receipt_thank_you_text', 'Terima kasih atas kunjungan Anda!'),
            'receipt_left_signature_label'  => Setting::get('receipt_left_signature_label', 'Penerima / Member'),
            'receipt_right_signature_label' => Setting::get('receipt_right_signature_label', 'Hormat Kami,'),
            'receipt_right_signature_name'  => Setting::get('receipt_right_signature_name', 'Administrator'),

            // 3. Pengaturan Laporan PDF
            'report_header_name'        => Setting::get('report_header_name', 'Kopi Elang Emas'),
            'report_subtitle'           => Setting::get('report_subtitle', 'Manajemen Kopi & Produksi Terintegrasi'),
            'report_footer_note'        => Setting::get('report_footer_note', ''),
            'report_prepared_by_label'  => Setting::get('report_prepared_by_label', 'Staf Administrasi'),
            'report_approved_by_label'  => Setting::get('report_approved_by_label', 'Pemilik Gudang / Owner'),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Simpan perubahan pengaturan
     */
    public function update(Request $request)
    {
        $data = $request->validate([
            // Identitas Usaha
            'shop_name'                 => 'required|string|max:255',
            'shop_tagline'              => 'nullable|string|max:255',
            'shop_address'              => 'required|string',
            'shop_phone'                => 'required|string|max:50',
            'shop_email'                => 'required|email|max:255',

            // Pengaturan Nota Penjualan
            'receipt_title'                 => 'nullable|string|max:255',
            'footer_note'                   => 'required|string',
            'receipt_thank_you_text'        => 'nullable|string|max:255',
            'receipt_left_signature_label'  => 'nullable|string|max:100',
            'receipt_right_signature_label' => 'nullable|string|max:100',
            'receipt_right_signature_name'  => 'nullable|string|max:100',

            // Pengaturan Laporan PDF
            'report_header_name'        => 'nullable|string|max:255',
            'report_subtitle'           => 'nullable|string|max:255',
            'report_footer_note'        => 'nullable|string',
            'report_prepared_by_label'  => 'nullable|string|max:255',
            'report_approved_by_label'  => 'nullable|string|max:255',
        ]);

        foreach ($data as $key => $value) {
            Setting::set($key, $value ?? '');
        }

        return back()->with('success', 'Pengaturan berhasil diperbarui.');
    }
}
