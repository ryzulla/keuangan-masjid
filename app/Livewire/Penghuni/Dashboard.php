<?php

namespace App\Livewire\Penghuni;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Auth;
use App\Models\Notice;
use App\Models\Campaign;
use App\Models\IplBilling;
use App\Models\Account;
use App\Models\CitizenReport;
use App\Models\ResidentHouseBlock;
use App\Models\HouseBlock;

#[Layout('layouts.penghuni')]
class Dashboard extends Component
{
    public bool $showHealthModal = false;
    public string $healthCategory = 'sakit';
    public string $healthReportFor = 'diri_sendiri';
    public string $healthPersonName = '';
    public string $healthDescription = '';

    protected $rules = [
        'healthCategory'    => 'required|in:sakit,meninggal,lainnya',
        'healthReportFor'   => 'required|in:diri_sendiri,keluarga,warga_lain',
        'healthPersonName'  => 'required_if:healthReportFor,keluarga,warga_lain|nullable|string|max:255',
        'healthDescription' => 'required|string|max:1000',
    ];

    protected $messages = [
        'healthCategory.required'    => 'Pilih jenis laporan.',
        'healthReportFor.required'   => 'Pilih untuk siapa laporan ini.',
        'healthPersonName.required_if'=> 'Nama wajib diisi jika bukan untuk diri sendiri.',
        'healthDescription.required' => 'Deskripsi wajib diisi.',
    ];

    #[Computed]
    public function accounts()
    {
        return Account::orderBy('organization_type')->orderBy('name')->get();
    }

    public function openHealthModal(): void
    {
        $this->showHealthModal = true;
    }

    public function dismissHealth(): void
    {
        $this->showHealthModal = false;
    }

    public function submitHealthReport(): void
    {
        $this->validate();

        $resident = Auth::guard('resident')->user();

        $reportFor = match($this->healthReportFor) {
            'diri_sendiri' => $resident->name,
            default        => $this->healthPersonName,
        };

        $categoryLabel = match($this->healthCategory) {
            'sakit'    => 'Kondisi Sakit',
            'meninggal'=> 'Berita Duka',
            'lainnya'  => 'Info Lainnya',
        };

        CitizenReport::create([
            'resident_id'  => $resident->id,
            'category'     => $this->healthCategory,
            'report_for'   => $this->healthReportFor,
            'person_name'  => $this->healthPersonName ?: null,
            'description'  => $this->healthDescription,
            'status'       => 'pending',
        ]);

        $resident->update(['last_health_report_at' => now()]);

        $this->showHealthModal = false;
        $this->healthCategory = 'sakit';
        $this->healthReportFor = 'diri_sendiri';
        $this->healthPersonName = '';
        $this->healthDescription = '';

        session()->flash('success', 'Laporan berhasil dikirim. Terima kasih atas kepedulian Anda.');
    }

    public function render()
    {
        $resident = Auth::guard('resident')->user()
            ->load(['currentAssignments.houseBlock', 'familyMembers']);

        // ─── Alerts ───────────────────────────────────────────────────────
        $unpaidBillings = IplBilling::with('period', 'houseBlock')
            ->where('responsible_resident_id', $resident->id)
            ->where('status', '!=', 'paid')
            ->orderBy('due_date')
            ->get();

        $totalOutstanding = $unpaidBillings->sum('outstanding');

        $pendingRequests = $resident->paymentRequests()
            ->where('status', 'pending')
            ->count();

        $expiringContracts = ResidentHouseBlock::whereHas('houseBlock', function ($q) use ($resident) {
                $q->whereHas('owners', fn($oq) => $oq->where('residents.id', $resident->id));
            })
            ->whereNull('ended_at')
            ->where('ownership_type', '!=', 'pemilik')
            ->whereNotNull('contract_end_date')
            ->where('contract_end_date', '<=', now()->addDays(30))
            ->with(['resident', 'houseBlock'])
            ->get();

        // ─── Notices ──────────────────────────────────────────────────────
        $notices = Notice::active()->withCount('likers')->latest()->take(3)->get();
        $likedNoticeIds = \Illuminate\Support\Facades\DB::table('notice_likes')
            ->where('resident_id', $resident->id)
            ->pluck('notice_id')
            ->all();

        // ─── Listed Houses ────────────────────────────────────────────────
        $listedHouses = HouseBlock::whereHas('owners', fn($q) => $q->where('residents.id', $resident->id))
            ->where('is_for_rent', true)
            ->with('photos')
            ->get();

        // ─── IPL Terbaru (hanya belum lunas) ──────────────────────────────
        $recentBillings = $unpaidBillings->take(3);

        // ─── Program Aktif ───────────────────────────────────────────────
        $campaigns = Campaign::where('status', 'active')
            ->latest()->take(3)->get();

        return view('livewire.penghuni.dashboard', compact(
            'resident', 'unpaidBillings', 'totalOutstanding',
            'pendingRequests', 'expiringContracts', 'notices', 'likedNoticeIds',
            'listedHouses', 'recentBillings', 'campaigns'
        ));
    }

    public function toggleLike(int $noticeId): void
    {
        $resident = Auth::guard('resident')->user();
        if (! $resident) {
            return;
        }
        Notice::active()->find($noticeId)?->likers()->toggle([$resident->id]);
    }
}
