<?php

namespace App\Notifications;

use App\Services\ResendApiMailer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

/**
 * VerifyEmailViaResend
 *
 * Notifikasi verifikasi email kustom menggunakan Resend API (HTTPS 443).
 * Menggantikan notifikasi default Laravel yang menggunakan mailer/SMTP.
 *
 * Implementasi mengikuti pola Illuminate\Auth\Notifications\VerifyEmail
 * tetapi pengiriman dilakukan langsung via ResendApiMailer.
 */
class VerifyEmailViaResend extends Notification
{
    use Queueable;

    /**
     * Kirim notifikasi via channel 'resend_api' (custom driver kita sendiri).
     * Kita tidak menggunakan channel 'mail' agar tidak melalui mailer Laravel.
     */
    public function via(object $notifiable): array
    {
        return ['database_skip']; // Placeholder — kita override send() langsung
    }

    /**
     * Override send() agar pengiriman dilakukan langsung via ResendApiMailer.
     * Ini memungkinkan kita mengirim email tanpa perlu driver mail Laravel.
     */
    public function send(mixed $notifiable, mixed $channel = null): void
    {
        $verificationUrl = $this->buildVerificationUrl($notifiable);
        $appName = config('app.name', 'Manajemen Kopi');

        $htmlBody = $this->buildHtmlEmail($notifiable, $verificationUrl, $appName);

        $mailer = app(ResendApiMailer::class);
        $success = $mailer->send(
            toEmail: $notifiable->email,
            toName: $notifiable->name,
            subject: "Verifikasi Alamat Email Anda — {$appName}",
            htmlBody: $htmlBody,
        );

        if (! $success) {
            Log::warning('[VerifyEmailViaResend] Email verifikasi gagal dikirim.', [
                'user_id' => $notifiable->id,
                'email'   => $notifiable->email,
            ]);
            // Tidak throw Exception — agar registrasi tetap berhasil
            // bahkan jika email gagal dikirim
        }
    }

    /**
     * Bangun signed URL verifikasi email (berlaku 60 menit).
     */
    protected function buildVerificationUrl(mixed $notifiable): string
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id'   => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }

    /**
     * Bangun body email HTML.
     */
    protected function buildHtmlEmail(mixed $notifiable, string $url, string $appName): string
    {
        $name      = htmlspecialchars($notifiable->name, ENT_QUOTES, 'UTF-8');
        $safeUrl   = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
        $safeApp   = htmlspecialchars($appName, ENT_QUOTES, 'UTF-8');

        return <<<HTML
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email — {$safeApp}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f4f4f5;
            margin: 0;
            padding: 0;
        }
        .wrapper {
            max-width: 560px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #3b5e3a;
            padding: 32px 40px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            font-size: 22px;
            margin: 0;
            font-weight: 600;
            letter-spacing: -0.3px;
        }
        .body {
            padding: 40px;
            color: #374151;
            font-size: 15px;
            line-height: 1.7;
        }
        .body p {
            margin: 0 0 16px 0;
        }
        .button-wrap {
            text-align: center;
            margin: 32px 0;
        }
        .button {
            display: inline-block;
            background-color: #3b5e3a;
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 32px;
            border-radius: 6px;
            font-size: 15px;
            font-weight: 600;
        }
        .footer {
            background-color: #f9fafb;
            padding: 20px 40px;
            font-size: 13px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
        }
        .url-fallback {
            font-size: 12px;
            color: #9ca3af;
            word-break: break-all;
            margin-top: 8px;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="header">
        <h1>☕ {$safeApp}</h1>
    </div>
    <div class="body">
        <p>Halo, <strong>{$name}</strong>!</p>
        <p>
            Terima kasih sudah mendaftar di <strong>{$safeApp}</strong>.
            Klik tombol di bawah untuk memverifikasi alamat email Anda.
        </p>
        <div class="button-wrap">
            <a href="{$safeUrl}" class="button">Verifikasi Email Saya</a>
        </div>
        <p>
            Tautan verifikasi ini akan kedaluwarsa dalam <strong>60 menit</strong>.
            Jika Anda tidak mendaftar, abaikan email ini.
        </p>
        <p class="url-fallback">
            Jika tombol tidak berfungsi, salin tautan berikut ke browser Anda:<br>
            {$safeUrl}
        </p>
    </div>
    <div class="footer">
        Email ini dikirim secara otomatis. Mohon jangan membalas email ini.<br>
        &copy; {$safeApp}
    </div>
</div>
</body>
</html>
HTML;
    }
}
