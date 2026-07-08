<?php
namespace App\Livewire\Reports;

use Livewire\Component;
use App\Models\Account;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class BalanceSheetReport extends Component
{
    public string $activeOrg = 'perumahan';
    public float $totalLiabilitas = 0;

    public function render()
    {
        $asetLancar = Account::orderBy('name')
            ->when($this->activeOrg !== 'semua', fn($q) => $q->where('organization_type', $this->activeOrg))
            ->get();
        $totalAset = $asetLancar->sum('balance');
        $totalEkuitas = $totalAset - $this->totalLiabilitas;

        return view('livewire.reports.balance-sheet-report', compact('asetLancar', 'totalAset', 'totalEkuitas'));
    }
}
