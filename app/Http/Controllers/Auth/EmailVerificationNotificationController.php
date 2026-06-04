<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Kirim ulang notifikasi verifikasi email.
     * Kegagalan pengiriman email dicatat di log dan tidak menyebabkan error 500.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('home', absolute: false));
        }

        try {
            $request->user()->sendEmailVerificationNotification();
        } catch (\Throwable $e) {
            Log::error('[VerifyEmail] Gagal mengirim ulang email verifikasi.', [
                'user_id' => $request->user()->id,
                'email'   => $request->user()->email,
                'error'   => $e->getMessage(),
            ]);

            return back()->with(
                'resend_error',
                'Email verifikasi belum dapat dikirim saat ini. Silakan coba beberapa saat lagi atau hubungi admin.'
            );
        }

        return back()->with('status', 'verification-link-sent');
    }
}
