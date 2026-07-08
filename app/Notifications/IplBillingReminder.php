<?php
namespace App\Notifications;

use Illuminate\Notifications\Notification;

class IplBillingReminder extends Notification
{
    public function __construct(
        public string $periodLabel,
        public float  $outstanding,
        public ?string $blockCode = null,
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        $amountFmt = 'Rp ' . number_format($this->outstanding, 0, ',', '.');
        $msg = "Tagihan IPL periode {$this->periodLabel}";
        if ($this->blockCode) $msg .= " (Blok {$this->blockCode})";
        $msg .= " sebesar {$amountFmt} belum dibayar. Segera lakukan pembayaran.";

        return [
            'title'   => 'Tagihan IPL Belum Dibayar',
            'message' => $msg,
            'type'    => 'ipl_reminder',
            'url'     => '/penghuni/ipl',
        ];
    }
}
