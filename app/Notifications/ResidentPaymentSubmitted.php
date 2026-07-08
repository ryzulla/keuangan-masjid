<?php
namespace App\Notifications;

use Illuminate\Notifications\Notification;

class ResidentPaymentSubmitted extends Notification
{
    public function __construct(
        public string $residentName,
        public string $type,
        public float  $amount,
        public ?string $blockCode = null,
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        $typeLabel = $this->type === 'ipl' ? 'IPL' : 'Donasi';
        $amountFmt = 'Rp ' . number_format($this->amount, 0, ',', '.');
        $msg = "{$this->residentName}" . ($this->blockCode ? " (Blok {$this->blockCode})" : '') .
               " mengirim konfirmasi {$typeLabel} {$amountFmt}. Menunggu verifikasi.";

        return [
            'title'   => "Konfirmasi {$typeLabel} Masuk",
            'message' => $msg,
            'type'    => 'payment_submitted',
            'url'     => '/payment-requests',
        ];
    }
}
