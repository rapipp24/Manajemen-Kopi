<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Tampilkan halaman registrasi publik.
     * Halaman ini HANYA untuk calon Sales.
     * Tidak ada pilihan role di view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Proses pendaftaran akun Sales baru oleh publik.
     *
     * Keputusan bisnis:
     * - Role otomatis = 'sales'. Tidak boleh diubah dari request publik.
     * - is_active = false  (menunggu approval admin)
     * - approval_status = 'pending' (menunggu approval admin)
     * - Event Registered dipanggil agar Laravel mengirim email verification link.
     * - User TIDAK langsung diloginkan. Diarahkan ke halaman login dengan pesan info.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            // Nama: wajib, minimal 3 karakter, maksimal 255, hanya huruf (Unicode),
            // spasi, titik, apostrof, tanda hubung — menolak nama hanya angka/simbol.
            'name'     => ['required', 'string', 'min:3', 'max:255', 'regex:/^[\pL\s.\'\-]+$/u'],

            // Email: wajib, lowercase, validasi RFC + regex TLD (menolak domain tidak valid seperti
            // jjadjaw@asdjawdj, abc@, abc, abc@example), unik di tabel users.
            // Catatan: email:rfc digunakan (bukan email:rfc,dns) agar pengujian
            // otomatis tidak bergantung pada DNS lookup jaringan.
            // Regex tambahan memastikan domain memiliki TLD (minimal satu titik di bagian domain).
            'email'    => ['required', 'string', 'lowercase', 'email:rfc', 'max:255', 'unique:' . User::class, 'regex:/^[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}$/'],

            // Password: wajib, terkonfirmasi, minimal 8 karakter, harus ada huruf besar,
            // huruf kecil, dan angka. Simbol tidak diwajibkan agar sales tidak kesulitan.
            'password' => ['required', 'confirmed', Rules\Password::min(8)->mixedCase()->numbers()],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'name.min'      => 'Nama minimal 3 karakter.',
            'name.max'      => 'Nama maksimal 255 karakter.',
            'name.regex'    => 'Nama hanya boleh berisi huruf, spasi, titik, apostrof, atau tanda hubung. Nama tidak boleh hanya angka atau simbol.',

            'email.required' => 'Email wajib diisi.',
            'email.email'    => 'Format email tidak valid. Masukkan alamat email yang benar, contoh: nama@domain.com.',
            'email.unique'   => 'Email ini sudah terdaftar. Silakan gunakan email lain atau masuk ke akun Anda.',

            'password.required'   => 'Kata sandi wajib diisi.',
            'password.confirmed'  => 'Konfirmasi kata sandi tidak cocok.',
            'password.min'        => 'Kata sandi minimal 8 karakter.',
            'password.mixed_case' => 'Kata sandi harus mengandung huruf besar dan huruf kecil.',
            'password.numbers'    => 'Kata sandi harus mengandung minimal satu angka.',
        ]);

        $user = User::create([
            'name'             => $request->name,
            'email'            => $request->email,
            'password'         => Hash::make($request->password),
            // Role selalu 'sales' — tidak dibaca dari request publik
            'role'             => User::ROLE_SALES,
            // is_active false sampai admin menyetujui
            'is_active'        => false,
            // Approval fields — eksplisit set pending
            'approval_status'  => User::APPROVAL_PENDING,
            'approved_at'      => null,
            'approved_by'      => null,
            'rejected_at'      => null,
            'rejection_reason' => null,
        ]);

        // Panggil event Registered agar Laravel email verification link dikirim
        event(new Registered($user));

        // Jangan langsung login user — arahkan ke halaman login dengan pesan info
        return redirect()->route('login')->with(
            'status',
            'Pendaftaran berhasil! Silakan cek email Anda untuk memverifikasi akun. Setelah email diverifikasi, akun Anda akan menunggu persetujuan Admin sebelum bisa digunakan.'
        );
    }
}
