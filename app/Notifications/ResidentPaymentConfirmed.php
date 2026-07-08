<?php
namespace App\Notifications;

use Illuminate\Notifications\Notification;

class ResidentPaymentConfirmed extends Notification
{
    public function __construct(
        public string  $type,
        public float   $amount,
        public ?string $periodLabel = null,
        public ?string $adminNotes  = null,
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        $typeLabel = $this->type === 'ipl' ? 'IPL' : 'Donasi';
        $amountFmt = 'Rp ' . number_format($this->amount, 0, ',', '.');
        $msg = "Pembayaran {$typeLabel} Anda sebesar {$amountFmt} telah dikonfirmasi dan dicatat.";
        if ($this->periodLabel) $msg .= " Periode: {$this->periodLabel}.";
        if ($this->adminNotes)  $msg .= " Catatan: {$this->adminNotes}";

        return [
            'title'   => 'Pembayaran Dikonfirmasi ✓',
            'message' => $msg,
            'type'    => 'payment_confirmed',
            'url'     => $this->type === 'ipl' ? '/penghuni/ipl' : '/penghuni/program',
        ];
    }
}
