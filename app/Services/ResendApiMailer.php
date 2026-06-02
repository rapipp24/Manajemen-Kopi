<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * ResendApiMailer
 *
 * Mengirim email melalui Resend API via HTTPS port 443.
 * Digunakan karena SMTP port 587/465 diblokir di VPS production.
 *
 * Dokumentasi Resend: https://resend.com/docs/api-reference/emails/send-email
 */
class ResendApiMailer
{
    private const API_URL = 'https://api.resend.com/emails';

    /**
     * Kirim satu email menggunakan Resend API.
     *
     * @param  string       $toEmail     Alamat email tujuan
     * @param  string       $toName      Nama penerima
     * @param  string       $subject     Subjek email
     * @param  string       $htmlBody    Isi email dalam format HTML
     * @return bool         true jika berhasil, false jika gagal
     */
    public function send(string $toEmail, string $toName, string $subject, string $htmlBody): bool
    {
        $apiKey  = config('services.resend.key');
        $fromAddress = config('services.resend.from_address');
        $fromName    = config('services.resend.from_name');

        if (empty($apiKey)) {
            Log::error('[ResendApiMailer] RESEND_API_KEY tidak dikonfigurasi.');
            return false;
        }

        if (empty($fromAddress)) {
            Log::error('[ResendApiMailer] RESEND_FROM_ADDRESS tidak dikonfigurasi.');
            return false;
        }

        $from = $fromName ? "{$fromName} <{$fromAddress}>" : $fromAddress;
        $to   = $toName   ? "{$toName} <{$toEmail}>"       : $toEmail;

        try {
            $response = Http::timeout(15)
                ->withToken($apiKey, 'Bearer')
                ->post(self::API_URL, [
                    'from'    => $from,
                    'to'      => [$to],
                    'subject' => $subject,
                    'html'    => $htmlBody,
                ]);

            if ($response->successful()) {
                Log::info('[ResendApiMailer] Email berhasil dikirim.', [
                    'to'      => $toEmail,
                    'subject' => $subject,
                    'resend_id' => $response->json('id'),
                ]);
                return true;
            }

            Log::error('[ResendApiMailer] Resend API mengembalikan error.', [
                'to'          => $toEmail,
                'subject'     => $subject,
                'status'      => $response->status(),
                'body'        => $response->body(),
            ]);
            return false;

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('[ResendApiMailer] Gagal konek ke Resend API.', [
                'to'      => $toEmail,
                'subject' => $subject,
                'error'   => $e->getMessage(),
            ]);
            return false;
        } catch (\Throwable $e) {
            Log::error('[ResendApiMailer] Exception saat kirim email.', [
                'to'      => $toEmail,
                'subject' => $subject,
                'error'   => $e->getMessage(),
            ]);
            return false;
        }
    }
}
