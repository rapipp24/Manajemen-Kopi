<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email'    => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * Urutan pemeriksaan yang aman:
     * 1. Cek rate limit terlebih dahulu.
     * 2. Cari user berdasarkan email.
     * 3. Jika email tidak ditemukan → gagal generik (tidak bocorkan info).
     * 4. Jika password salah → gagal generik + hit rate limiter.
     * 5. Password benar → BARU cek status akun:
     *    a. Email belum diverifikasi
     *    b. Approval pending
     *    c. Approval rejected
     *    d. is_active false
     * 6. Semua valid → login.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // Langkah 1: Cari user berdasarkan email (case-insensitive)
        $user = User::where('email', Str::lower($this->string('email')))->first();

        // Langkah 2: Jika user tidak ada atau password salah → gagal generik
        if (! $user || ! \Illuminate\Support\Facades\Hash::check($this->string('password'), $user->password)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        // Langkah 3: Password benar → cek status akun
        // (hanya reveal masalah akun setelah password terbukti benar)

        // 3a. Email belum diverifikasi
        if (! $user->hasVerifiedEmail()) {
            throw ValidationException::withMessages([
                'email' => 'Silakan verifikasi email Anda terlebih dahulu. Cek inbox atau folder spam Anda.',
            ]);
        }

        // 3b. Approval pending (berlaku untuk sales yang baru mendaftar)
        if ($user->isPending()) {
            throw ValidationException::withMessages([
                'email' => 'Pendaftaran Anda sedang menunggu persetujuan Admin. Anda akan dihubungi jika akun sudah aktif.',
            ]);
        }

        // 3c. Approval rejected
        if ($user->isRejected()) {
            $message = 'Pendaftaran Anda ditolak oleh Admin.';
            if ($user->rejection_reason) {
                $message .= ' Alasan: ' . $user->rejection_reason;
            }
            throw ValidationException::withMessages([
                'email' => $message,
            ]);
        }

        // 3d. User dinonaktifkan oleh admin (is_active = false)
        if (! $user->is_active) {
            throw ValidationException::withMessages([
                'email' => 'Akun Anda telah dinonaktifkan. Silakan hubungi Administrator.',
            ]);
        }

        // Langkah 4: Semua valid → login menggunakan Auth::attempt
        // Auth::attempt dipanggil sebagai mekanisme resmi Laravel untuk login
        // (mengelola session, remember token, dll.)
        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            // Fallback: seharusnya tidak terjadi karena password sudah diverifikasi manual di atas
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        // Login berhasil → clear rate limiter
        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')) . '|' . $this->ip());
    }
}
