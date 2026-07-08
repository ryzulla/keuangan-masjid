<?php
namespace App\Notifications;

use Illuminate\Notifications\Notification;

class ResidentPaymentRejected extends Notification
{
    public function __construct(
        public string  $type,
        public float   $amount,
        public ?string $reason = null,
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        $typeLabel = $this->type === 'ipl' ? 'IPL' : 'Donasi';
        $amountFmt = 'Rp ' . number_format($this->amount, 0, ',', '.');
        $msg = "Konfirmasi {$typeLabel} {$amountFmt} Anda tidak dapat diproses.";
        if ($this->reason) $msg .= " Alasan: {$this->reason}";
        $msg .= " Silakan hubungi pengurus untuk informasi lebih lanjut.";

        return [
            'title'   => 'Pembayaran Tidak Dikonfirmasi',
            'message' => $msg,
            'type'    => 'payment_rejected',
            'url'     => $this->type === 'ipl' ? '/penghuni/ipl' : '/penghuni/program',
        ];
    }
}
