<?php

namespace App\Notifications;

use App\Models\EmergencyAlert;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class EmergencyAlertNotification extends Notification
{
    use Queueable;

    public function __construct(
        public EmergencyAlert $alert,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title'      => '⚠️ DARURAT',
            'message'    => "Blok {$this->alert->block_code}: {$this->alert->message}",
            'type'       => 'emergency',
            'alert_id'   => $this->alert->id,
            'block_code' => $this->alert->block_code,
            'url'        => route('penghuni.dashboard'),
        ];
    }
}
