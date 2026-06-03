<?php

namespace App\Notifications\Channels;

use App\Services\ResendApiMailer;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class ResendChannel
{
    protected ResendApiMailer $mailer;

    public function __construct(ResendApiMailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Kirim notifikasi yang diberikan.
     */
    public function send(mixed $notifiable, Notification $notification): void
    {
        if (! method_exists($notification, 'toResend')) {
            return;
        }

        $message = $notification->toResend($notifiable);

        if (
            ! is_array($message) ||
            empty($message['toEmail']) ||
            empty($message['subject']) ||
            empty($message['htmlBody'])
        ) {
            Log::warning('[ResendChannel] Payload email Resend tidak valid.', [
                'notifiable_id' => method_exists($notifiable, 'getKey') ? $notifiable->getKey() : null,
                'notification'  => get_class($notification),
            ]);
            return;
        }

        $success = $this->mailer->send(
            toEmail: $message['toEmail'],
            toName:  $message['toName'] ?? '',
            subject: $message['subject'],
            htmlBody: $message['htmlBody']
        );

        if (! $success) {
            Log::warning('[ResendChannel] Gagal mengirim email via Resend.', [
                'notifiable_id' => method_exists($notifiable, 'getKey') ? $notifiable->getKey() : null,
                'email'         => $message['toEmail'] ?? null,
                'subject'       => $message['subject'] ?? null,
            ]);
        }
    }
}
