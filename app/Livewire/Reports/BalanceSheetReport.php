<?php
namespace App\Livewire\Reports;

use Livewire\Component;
use App\Models\Account;
use Livewire\Attributes\Layout; // <-- Penting untuk layout

#[Layout('layouts.app')] // <-- Menentukan layout utama
class BalanceSheetReport extends Component
{
    public $asetLancar;
    public $totalAset;
    public $totalLiabilitas = 0; // Diasumsikan 0 untuk masjid
    public $totalEkuitas;

    public function mount()
    {
        $this->asetLancar = Account::orderBy('name')->get();
        $this->totalAset = $this->asetLancar->sum('balance');
        $this->totalEkuitas = $this->totalAset - $this->totalLiabilitas;
    }

    public function render()
    {
        return view('livewire.reports.balance-sheet-report');
    }
}
