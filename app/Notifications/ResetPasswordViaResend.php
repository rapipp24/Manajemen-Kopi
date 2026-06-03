<?php

namespace App\Notifications;

use App\Notifications\Channels\ResendChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * ResetPasswordViaResend
 *
 * Notifikasi reset password kustom menggunakan Resend API (HTTPS 443).
 * Menggantikan notifikasi default Laravel yang menggunakan mailer/SMTP.
 */
class ResetPasswordViaResend extends Notification
{
    use Queueable;

    /**
     * Token reset password.
     */
    public string $token;

    /**
     * Buat instance notifikasi baru.
     */
    public function __construct(string $token)
    {
        $this->token = $token;
    }

    /**
     * Kirim notifikasi via channel ResendChannel kustom.
     */
    public function via(object $notifiable): array
    {
        return [ResendChannel::class];
    }

    /**
     * Ambil representasi Resend dari notifikasi.
     */
    public function toResend(mixed $notifiable): array
    {
        $resetUrl = route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], true);

        $htmlBody = $this->buildHtmlEmail($notifiable, $resetUrl);

        return [
            'toEmail'  => $notifiable->getEmailForPasswordReset(),
            'toName'   => $notifiable->name ?? '',
            'subject'  => 'Reset Password - Kopi Elang Emas',
            'htmlBody' => $htmlBody,
        ];
    }

    /**
     * Bangun body email HTML dengan branding Kopi Elang Emas.
     * Warna brand: header #6B2E16 (maroon kopi), button #A3470D, background #F7F2EC.
     * Logo menggunakan absolute URL (wajib untuk email client).
     */
    protected function buildHtmlEmail(mixed $notifiable, string $url): string
    {
        $name        = htmlspecialchars($notifiable->name ?? '', ENT_QUOTES, 'UTF-8');
        $safeUrl     = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');

        // Absolute URL logo untuk email client (wajib HTTPS, tidak boleh path relatif)
        $logoUrl     = rtrim(config('app.url'), '/') . '/images/LOGO-KOPI-ELANG-EMAS.jpg';
        $safeLogoUrl = htmlspecialchars($logoUrl, ENT_QUOTES, 'UTF-8');

        return <<<HTML
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Kopi Elang Emas</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #F7F2EC;
            margin: 0;
            padding: 0;
        }
        .wrapper {
            max-width: 560px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .header {
            background-color: #6B2E16;
            padding: 28px 40px;
            text-align: center;
        }
        .header-logo {
            max-height: 64px;
            width: auto;
            display: block;
            margin: 0 auto;
        }
        .header-brand {
            color: #F7E8D6;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-top: 10px;
            margin-bottom: 0;
        }
        .body {
            padding: 36px 40px;
            color: #1F2937;
            font-size: 15px;
            line-height: 1.75;
        }
        .body p {
            margin: 0 0 16px 0;
        }
        .greeting {
            font-size: 17px;
            font-weight: 600;
            color: #1F2937;
        }
        .button-wrap {
            text-align: center;
            margin: 32px 0;
        }
        .button {
            display: inline-block;
            background-color: #A3470D;
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 36px;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 700;
            letter-spacing: 0.2px;
        }
        .divider {
            height: 1px;
            background-color: #EDE0D4;
            margin: 8px 0 24px 0;
        }
        .url-fallback {
            font-size: 12px;
            color: #9ca3af;
            word-break: break-all;
            margin-top: 4px;
            line-height: 1.5;
        }
        .footer {
            background-color: #F7F2EC;
            padding: 18px 40px;
            font-size: 12px;
            color: #9E7353;
            border-top: 1px solid #EDE0D4;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="header">
        <img src="{$safeLogoUrl}"
             alt="Kopi Elang Emas"
             class="header-logo"
             width="64"
             height="64">
        <p class="header-brand">Kopi Elang Emas</p>
    </div>
    <div class="body">
        <p class="greeting">Halo, {$name}!</p>
        <p>
            Anda menerima email ini karena kami menerima permintaan reset password untuk akun Anda di <strong>Kopi Elang Emas</strong>.
            Silakan klik tombol di bawah untuk membuat password baru.
        </p>
        <div class="button-wrap">
            <a href="{$safeUrl}" class="button">Reset Password</a>
        </div>
        <p style="color: #6B7280; font-size: 14px;">
            Tautan reset password ini berlaku selama <strong>60 menit</strong>.
            Jika Anda tidak meminta reset password, Anda dapat mengabaikan email ini dengan aman.
        </p>
        <div class="divider"></div>
        <p class="url-fallback">
            Jika tombol tidak berfungsi, salin dan tempel tautan berikut ke browser Anda:<br>
            {$safeUrl}
        </p>
    </div>
    <div class="footer">
        Email ini dikirim secara otomatis &mdash; mohon jangan membalas email ini.<br>
        &copy; Kopi Elang Emas
    </div>
</div>
</body>
</html>
HTML;
    }
}
