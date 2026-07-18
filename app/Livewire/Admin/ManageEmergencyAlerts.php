<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\EmergencyAlert;

#[Layout('layouts.app')]
class ManageEmergencyAlerts extends Component
{
    use WithPagination;

    public string $filterStatus = 'all';

    public function stopAlert(EmergencyAlert $alert): void
    {
        $alert->stop(auth()->id());
        session()->flash('success', 'Alert darurat berhasil dihentikan.');
    }

    public function clearAll(): void
    {
        $active = EmergencyAlert::active()->count();
        EmergencyAlert::active()->each(fn(EmergencyAlert $a) => $a->stop(auth()->id()));
        session()->flash('success', "{$active} alert aktif berhasil dihentikan.");
    }

    public function render()
    {
        $query = EmergencyAlert::with('resident', 'stopper')->latest();

        if ($this->filterStatus === 'active') {
            $query->active();
        } elseif ($this->filterStatus === 'stopped') {
            $query->where('is_active', false);
        }

        return view('livewire.admin.manage-emergency-alerts', [
            'alerts' => $query->paginate(10),
        ]);
    }
}
