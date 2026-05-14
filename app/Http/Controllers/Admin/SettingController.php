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
            'shop_name'    => Setting::get('shop_name', 'MANAJEMEN KOPI'),
            'shop_address' => Setting::get('shop_address', 'Jl. Kopi Nikmat No. 123, Indonesia'),
            'shop_phone'   => Setting::get('shop_phone', '(021) 1234-5678'),
            'shop_email'   => Setting::get('shop_email', 'hello@kopimanajer.com'),
            'footer_note'  => Setting::get('footer_note', 'Barang yang sudah dibeli tidak dapat ditukar atau dikembalikan kecuali ada perjanjian sebelumnya.'),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Simpan perubahan pengaturan
     */
    public function update(Request $request)
    {
        $data = $request->validate([
            'shop_name'    => 'required|string|max:255',
            'shop_address' => 'required|string',
            'shop_phone'   => 'required|string|max:50',
            'shop_email'   => 'required|email|max:255',
            'footer_note'  => 'required|string',
        ]);

        foreach ($data as $key => $value) {
            Setting::set($key, $value);
        }

        return back()->with('success', 'Pengaturan berhasil diperbarui.');
    }
}
