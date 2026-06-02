<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    /**
     * Tampilkan form pengaturan akun Sales.
     */
    public function edit()
    {
        $user = Auth::user();
        return view('sales.settings', compact('user'));
    }

    /**
     * Simpan perubahan data profil Sales.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // Validasi input
        $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:500'],
        ]);

        // Simpan hanya data yang diizinkan untuk diubah oleh Sales
        $user->update([
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
            'address' => $request->input('address'),
        ]);

        return redirect()->route('sales.settings')
            ->with('success', 'Profil berhasil diperbarui.');
    }
}
