<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class GoogleController extends Controller
{
    /**
     * Redirect ke halaman Google
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle callback dari Google
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Cari user berdasarkan google_id atau email
            $user = User::where('google_id', $googleUser->id)
                        ->orWhere('email', $googleUser->email)
                        ->first();

            if ($user) {
                // Jika user sudah ada, update google_id nya jika belum ada
                if (!$user->google_id) {
                    $user->update([
                        'google_id' => $googleUser->id,
                        'google_token' => $googleUser->token,
                    ]);
                }
                
                Auth::login($user);
            } else {
                // Jika user belum ada, buat akun baru sebagai Customer
                $newUser = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'google_token' => $googleUser->token,
                    'role' => 'user', // Otomatis jadi Customer
                    'email_verified_at' => now(), // Google sudah verifikasi emailnya
                    'password' => null, // Tidak butuh password manual
                ]);

                Auth::login($newUser);
            }

            // Redirect berdasarkan role
            if (auth()->user()->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('user.products');

        } catch (Exception $e) {
            return redirect()->route('login')->with('error', 'Gagal login dengan Google. Silakan coba lagi.');
        }
    }
}
