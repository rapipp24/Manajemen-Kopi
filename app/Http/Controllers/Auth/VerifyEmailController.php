<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class VerifyEmailController extends Controller
{
    /**
     * Mark the user's email address as verified.
     *
     * Route ini TIDAK memerlukan auth middleware agar user bisa
     * verifikasi email tanpa login terlebih dahulu.
     * Keamanan dijaga oleh signed URL + hash validation.
     */
    public function __invoke(Request $request): RedirectResponse
    {
        // Ambil user berdasarkan route parameter {id}
        $user = User::findOrFail($request->route('id'));

        // Validasi hash email — pastikan link memang untuk user ini
        if (! hash_equals(
            (string) $request->route('hash'),
            sha1($user->getEmailForVerification())
        )) {
            abort(403, 'Link verifikasi tidak valid.');
        }

        // Jika sudah pernah verified, redirect aman tanpa error
        if ($user->hasVerifiedEmail()) {
            return redirect()->route('verification.success')
                ->with('status', 'Email Anda sudah diverifikasi sebelumnya.');
        }

        // Mark email sebagai verified
        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        // Redirect ke halaman sukses — JANGAN login-kan user otomatis
        // User tetap harus menunggu admin approval sebelum bisa login
        return redirect()->route('verification.success')
            ->with('status', 'Email Anda berhasil diverifikasi. Silakan tunggu approval admin sebelum dapat login.');
    }
}
