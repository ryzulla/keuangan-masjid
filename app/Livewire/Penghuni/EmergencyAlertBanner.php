<?php

namespace App\Livewire\Penghuni;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;
use App\Models\EmergencyAlert;

class EmergencyAlertBanner extends Component
{
    public ?EmergencyAlert $activeAlert = null;
    public bool $isTriggeredByMe = false;
    public bool $showBanner = false;
    public ?int $lastSoundAlertId = null;
    public ?int $dismissUntil = null;

    public function mount(): void
    {
        $this->checkForActiveAlert();
    }

    #[On('emergency-triggered')]
    public function onEmergencyTriggered(): void
    {
        $this->checkForActiveAlert();
    }

    public function checkForActiveAlert(): void
    {
        $alert = EmergencyAlert::with('resident')
            ->active()
            ->latest()
            ->first();

        if ($alert) {
            $this->activeAlert = $alert;

            $resident = Auth::guard('resident')->user();
            $this->isTriggeredByMe = $resident && $alert->resident_id === $resident->id;

            if ($this->dismissUntil !== null && now()->timestamp < $this->dismissUntil) {
                $this->showBanner = false;
            } else {
                $this->showBanner = true;
                $this->dismissUntil = null;
            }

            if (!$this->isTriggeredByMe && $this->lastSoundAlertId !== $alert->id) {
                $this->lastSoundAlertId = $alert->id;
                $this->dispatch('alert-activated');
            }
        } else {
            $this->activeAlert = null;
            $this->isTriggeredByMe = false;
            $this->showBanner = false;
            $this->lastSoundAlertId = null;
            $this->dismissUntil = null;
        }
    }

    public function stopAlert(): void
    {
        $user = Auth::guard('resident')->user() ?? Auth::user();
        if (! $user || ! $this->activeAlert) {
            return;
        }

        $this->activeAlert->stop($user->id);
        $this->activeAlert = null;
        $this->showBanner = false;
        $this->dismissUntil = null;
    }

    public function dismissBanner(): void
    {
        $this->showBanner = false;
        $this->dismissUntil = now()->addSeconds(30)->timestamp;
    }

    public function render()
    {
        return view('livewire.penghuni.emergency-alert-banner');
    }
}
