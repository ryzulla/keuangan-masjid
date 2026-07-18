<?php
namespace App\Livewire\Penghuni;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use App\Models\HouseBlock;
use App\Models\IplBilling;
use App\Models\IplPeriod;
use App\Models\ResidentPaymentRequest;
use App\Models\User;
use App\Notifications\ResidentPaymentSubmitted;
use Carbon\Carbon;

#[Layout('layouts.penghuni')]
class IplPortal extends Component
{
    use WithFileUploads;

    // UI state (entangled with Alpine)
    public bool $showMatrix    = false;
    public bool $showMonitored = false;

    // Bayar satu tagihan (lunasi sisa)
    public bool $isPayModalOpen  = false;
    public ?int $payingBillingId = null;
    public bool $singlePaySec = true;
    public bool $singlePayGarb = true;
    public bool $singlePayKas = true;

    // Bayar via checklist (banyak bulan / sebagian per komponen / di muka)
    public bool  $isChecklistOpen   = false;
    public array $pickedMonths       = [];   // daftar key "Y-M" yang dicentang
    public bool  $paySecurity        = true;  // komponen yang dibayar (berlaku utk semua bulan tercentang)
    public bool  $payGarbage         = true;
    public bool  $payKasRt           = true;
    public int   $extraFutureMonths  = 0;     // jumlah bulan di muka yang ditambahkan
    public int   $futureMonthsToAdd  = 1;     // berapa bulan yg ditambahkan sekali klik

    // Field pembayaran bersama
    public string $paymentMethod = 'transfer';
    public string $bankName      = '';
    public string $referenceNum  = '';
    public        $proofPhoto    = null;
    public string $notes         = '';
    public string $amount        = '';

    // ─── Bayar satu tagihan (lunasi sisa) ────────────────────────────────────

    public function openPay(int $billingId): void
    {
        $billing = IplBilling::find($billingId);
        if (!$billing || $billing->responsible_resident_id !== Auth::guard('resident')->id()) return;

        if ($billing->status === 'paid') {
            session()->flash('error', 'Tagihan bulan ini sudah lunas.');
            return;
        }
        if (ResidentPaymentRequest::where('ipl_billing_id', $billing->id)->where('status', 'pending')->exists()) {
            session()->flash('error', 'Sudah ada konfirmasi pembayaran yang menunggu verifikasi untuk tagihan ini.');
            return;
        }

        $this->payingBillingId = $billingId;
        $this->singlePaySec    = $billing->remainingSecurity() > 0;
        $this->singlePayGarb   = $billing->remainingGarbage() > 0;
        $this->singlePayKas    = $billing->remainingKasRt() > 0;
        $this->amount          = (string) $billing->outstanding;
        $this->paymentMethod   = 'transfer';
        $this->bankName        = '';
        $this->referenceNum    = '';
        $this->proofPhoto      = null;
        $this->notes           = '';
        $this->isPayModalOpen  = true;
    }

    public function submitPayment(): void
    {
        $this->validate([
            'paymentMethod' => 'required|in:cash,transfer,other',
            'bankName'      => 'nullable|string|max:100',
            'referenceNum'  => 'nullable|string|max:100',
            'proofPhoto'    => 'nullable|image|max:3072',
            'notes'         => 'nullable|string|max:500',
        ]);

        $billing = IplBilling::with('period')->findOrFail($this->payingBillingId);
        if ($billing->responsible_resident_id !== Auth::guard('resident')->id()) return;

        if ($billing->status === 'paid') {
            $this->isPayModalOpen = false;
            session()->flash('error', 'Tagihan bulan ini sudah lunas.');
            return;
        }
        if (ResidentPaymentRequest::where('ipl_billing_id', $billing->id)->where('status', 'pending')->exists()) {
            $this->isPayModalOpen = false;
            session()->flash('error', 'Sudah ada konfirmasi pembayaran yang menunggu verifikasi untuk tagihan ini.');
            return;
        }

        $photoPath = $this->proofPhoto?->store('payment-proofs', 'public');
        $resident  = Auth::guard('resident')->user();
        $block     = $resident->currentAssignments()->with('houseBlock')->first()?->houseBlock;

        $amtS = $this->singlePaySec ? $billing->remainingSecurity() : 0;
        $amtG = $this->singlePayGarb ? $billing->remainingGarbage() : 0;
        $amtK = $this->singlePayKas ? $billing->remainingKasRt() : 0;
        $total = $amtS + $amtG + $amtK;

        if ($total <= 0) {
            $this->isPayModalOpen = false;
            session()->flash('error', 'Tidak ada komponen yang dipilih untuk dibayar.');
            return;
        }

        ResidentPaymentRequest::create([
            'resident_id'      => $resident->id,
            'type'             => 'ipl',
            'ipl_billing_id'   => $billing->id,
            'period_year'      => $billing->period?->year,
            'period_month'     => $billing->period?->month,
            'amount'           => $total,
            'amount_security'  => $amtS,
            'amount_garbage'   => $amtG,
            'amount_kas_rt'    => $amtK,
            'payment_method'   => $this->paymentMethod,
            'bank_name'        => $this->bankName ?: null,
            'reference_number' => $this->referenceNum ?: null,
            'proof_photo'      => $photoPath,
            'notes'            => $this->notes ?: null,
            'status'           => 'pending',
        ]);

        $this->notifyAdmins($resident, 'ipl', $total, $block?->block_code);
        $this->isPayModalOpen = false;
        session()->flash('success', 'Konfirmasi pembayaran IPL berhasil dikirim! Pengurus akan memverifikasi.');
    }

    // ─── Checklist pembayaran (banyak bulan / sebagian / di muka) ─────────────

    public function openChecklist(): void
    {
        $this->extraFutureMonths = 0;
        $this->futureMonthsToAdd = 1;
        $this->paySecurity  = true;
        $this->payGarbage   = true;
        $this->payKasRt     = true;
        $this->paymentMethod = 'transfer';
        $this->bankName      = '';
        $this->referenceNum  = '';
        $this->proofPhoto    = null;
        $this->notes         = '';

        // Pra-centang semua bulan yang bisa dibayar (tunggakan + berjalan), bukan bulan di muka.
        $resident = Auth::guard('resident')->user();
        $this->pickedMonths = $this->buildPayMonths($resident)
            ->filter(fn($m) => !$m['locked'] && !$m['is_future'] && $m['rem_total'] > 0)
            ->pluck('key')->values()->toArray();

        $this->isChecklistOpen = true;
    }

    public function addFutureMonth(): void
    {
        $count = max(1, min(12 - $this->extraFutureMonths, $this->futureMonthsToAdd));
        if ($count <= 0) return;

        $resident = Auth::guard('resident')->user();

        for ($i = 1; $i <= $count; $i++) {
            $this->extraFutureMonths++;
            $future = Carbon::now()->startOfMonth()->addMonths($this->extraFutureMonths);

            $assignments = $resident->currentAssignments()->with('houseBlock')->get();
            if ($assignments->isNotEmpty()) {
                foreach ($assignments as $assign) {
                    $key = $assign->house_block_id . '-' . $future->year . '-' . $future->month;
                    if (!in_array($key, $this->pickedMonths)) $this->pickedMonths[] = $key;
                }
            } else {
                $key = '0-' . $future->year . '-' . $future->month;
                if (!in_array($key, $this->pickedMonths)) $this->pickedMonths[] = $key;
            }
        }
    }

    public function submitChecklist(): void
    {
        $this->validate([
            'paymentMethod' => 'required|in:cash,transfer,other',
            'bankName'      => 'nullable|string|max:100',
            'referenceNum'  => 'nullable|string|max:100',
            'proofPhoto'    => 'nullable|image|max:3072',
            'notes'         => 'nullable|string|max:500',
        ]);

        if (!$this->paySecurity && !$this->payGarbage && !$this->payKasRt) {
            $this->addError('paySecurity', 'Pilih minimal satu komponen (Keamanan / Sampah / Kas RT).');
            return;
        }
        if (empty($this->pickedMonths)) {
            $this->addError('pickedMonths', 'Pilih minimal satu bulan yang akan dibayar.');
            return;
        }

        $resident = Auth::guard('resident')->user();
        $block    = $resident->currentAssignments()->with('houseBlock')->first()?->houseBlock;
        $months   = $this->buildPayMonths($resident)->keyBy('key');

        $photoPath = $this->proofPhoto?->store('payment-proofs', 'public');
        $created = 0; $skipped = 0; $totalSent = 0;
        $labels = [];

        foreach ($this->pickedMonths as $key) {
            $m = $months->get($key);
            if (!$m || $m['locked'] || $m['rem_total'] <= 0) { $skipped++; continue; }

            $amtS = $this->paySecurity ? $m['rem_security'] : 0;
            $amtG = $this->payGarbage  ? $m['rem_garbage']  : 0;
            $amtK = $this->payKasRt    ? $m['rem_kas_rt']   : 0;
            $total = $amtS + $amtG + $amtK;
            if ($total <= 0) { $skipped++; continue; }

            $parts = collect([
                $amtS > 0 ? 'Keamanan Rp ' . number_format($amtS, 0, ',', '.') : null,
                $amtG > 0 ? 'Sampah Rp '   . number_format($amtG, 0, ',', '.') : null,
                $amtK > 0 ? 'Kas RT Rp '   . number_format($amtK, 0, ',', '.') : null,
            ])->filter()->join(', ');

            ResidentPaymentRequest::create([
                'resident_id'      => $resident->id,
                'type'             => 'ipl',
                'ipl_billing_id'   => $m['billing_id'],
                'period_year'      => $m['year'],
                'period_month'     => $m['month'],
                'amount'           => $total,
                'amount_security'  => $amtS,
                'amount_garbage'   => $amtG,
                'amount_kas_rt'    => $amtK,
                'payment_method'   => $this->paymentMethod,
                'bank_name'        => $this->bankName ?: null,
                'reference_number' => $this->referenceNum ?: null,
                'proof_photo'      => $photoPath,
                'notes'            => trim("Pembayaran {$m['label']} — {$parts}." . ($this->notes ? " {$this->notes}" : '')),
                'status'           => 'pending',
            ]);

            $totalSent += $total;
            $labels[] = ($m['block_code'] ? '[' . $m['block_code'] . '] ' : '') . $m['label'];
            $created++;
        }

        if ($created === 0) {
            $this->isChecklistOpen = false;
            session()->flash('error', 'Tidak ada yang dikirim — bulan terpilih sudah lunas/menunggu, atau komponen tidak punya sisa.');
            return;
        }

        $this->notifyAdmins($resident, 'ipl', $totalSent, $block?->block_code);
        $this->isChecklistOpen = false;

        $msg = "Pembayaran IPL {$created} bulan (" . implode(', ', $labels) . ") berhasil dikirim! Total Rp " . number_format($totalSent, 0, ',', '.') . '.';
        if ($skipped > 0) $msg .= " {$skipped} bulan dilewati (sudah lunas/menunggu).";
        session()->flash('success', $msg);
    }

    // ─── Bangun daftar bulan yang bisa dibayar ────────────────────────────────

    /**
     * Kembalikan koleksi bulan (tunggakan → berjalan → di muka) dengan status &
     * sisa per komponen. Sumber tunggal untuk checklist & pratinjau.
     * Key diper-blok agar penghuni multi-blok tidak kehilangan data tagihan.
     */
    private function buildPayMonths($resident): \Illuminate\Support\Collection
    {
        $rid = $resident->id;
        $now = Carbon::now()->startOfMonth();

        // Tagihan per blok per periode.
        $billings = IplBilling::with(['period', 'houseBlock'])
            ->where('responsible_resident_id', $rid)
            ->get()
            ->filter(fn($b) => $b->period)
            ->keyBy(fn($b) => $b->house_block_id . '-' . $b->period->year . '-' . $b->period->month);

        // Pengajuan MENUNGGU (block-aware).
        $pendingReqs = ResidentPaymentRequest::where('resident_id', $rid)
            ->where('type', 'ipl')->where('status', 'pending')
            ->with('iplBilling')
            ->get(['period_year', 'period_month', 'ipl_billing_id']);

        $pendingKeys = $pendingReqs->map(function ($r) {
            if ($r->iplBilling) {
                return $r->iplBilling->house_block_id . '-' . $r->period_year . '-' . $r->period_month;
            }
            return '0-' . $r->period_year . '-' . $r->period_month;
        })->values()->toArray();

        // Tarif default (untuk bulan yang belum ada tagihannya).
        $periods = IplPeriod::get()->keyBy(fn($p) => $p->year . '-' . $p->month);
        $latest  = IplPeriod::latest('year')->latest('month')->first();

        // Rentang: tunggakan terlama (belum lunas) s/d bulan berjalan + bulan di muka.
        $start = $now->copy()->subMonths(5);
        $oldest = $billings->filter(fn($b) => $b->status !== 'paid')
            ->map(fn($b) => Carbon::create($b->period->year, $b->period->month, 1))->min();
        if ($oldest && $oldest->lt($start)) $start = $oldest->copy();
        $end = $now->copy()->addMonths(max(0, $this->extraFutureMonths));

        $out = collect();
        for ($c = $start->copy(); $c->lte($end); $c->addMonth()) {
            $periodKey = $c->year . '-' . $c->month;
            $isFuture  = $c->gt($now);

            // Cari tagihan untuk periode ini (per blok).
            $periodBillings = $billings->filter(fn($b, $k) => str_ends_with($k, '-' . $periodKey));

            if ($periodBillings->isNotEmpty()) {
                foreach ($periodBillings as $bk => $billing) {
                    $blockId = $billing->house_block_id;
                    $key     = $blockId . '-' . $periodKey;

                    $remS = $billing->remainingSecurity();
                    $remG = $billing->remainingGarbage();
                    $remK = $billing->remainingKasRt();
                    $status = $billing->is_waived && $billing->status === 'paid' ? 'waived' : $billing->status;

                    $isPending = in_array($key, $pendingKeys);
                    $remTotal  = $remS + $remG + $remK;
                    $locked    = $isPending || in_array($status, ['paid', 'waived']) || $remTotal <= 0;

                    $out->push([
                        'key'          => $key,
                        'year'         => $c->year,
                        'month'        => $c->month,
                        'block_id'     => $blockId,
                        'block_code'   => $billing->houseBlock?->block_code,
                        'label'        => $c->translatedFormat('F Y'),
                        'status'       => $isPending ? 'pending' : $status,
                        'locked'       => $locked,
                        'is_future'    => $isFuture,
                        'rem_security' => $remS,
                        'rem_garbage'  => $remG,
                        'rem_kas_rt'   => $remK,
                        'rem_total'    => $remTotal,
                        'billing_id'   => $billing->id,
                    ]);
                }
            } elseif ($isFuture) {
                // Bulan di muka tanpa tagihan — per blok sesuai assignment aktif.
                $assignments = $resident->currentAssignments()->with('houseBlock')->get();
                if ($assignments->isNotEmpty()) {
                    foreach ($assignments as $assign) {
                        $blockId = $assign->house_block_id;
                        $block   = $assign->houseBlock;

                        $p = $periods->get($periodKey) ?? $latest;
                        $remS = (float) ($p->ipl_security_amount ?? 0);
                        $remG = (float) ($p->ipl_garbage_amount ?? 0);
                        $remK = (float) ($p->ipl_kas_rt_amount ?? 0);

                        $key    = $blockId . '-' . $periodKey;
                        $isPending = in_array($key, $pendingKeys);
                        $remTotal  = $remS + $remG + $remK;
                        $locked    = $isPending || $remTotal <= 0;

                        $out->push([
                            'key'          => $key,
                            'year'         => $c->year,
                            'month'        => $c->month,
                            'block_id'     => $blockId,
                            'block_code'   => $block?->block_code,
                            'label'        => $c->translatedFormat('F Y'),
                            'status'       => $isPending ? 'pending' : 'unbilled',
                            'locked'       => $locked,
                            'is_future'    => $isFuture,
                            'rem_security' => $remS,
                            'rem_garbage'  => $remG,
                            'rem_kas_rt'   => $remK,
                            'rem_total'    => $remTotal,
                            'billing_id'   => null,
                        ]);
                    }
                } else {
                    // Fallback: entri tunggal tanpa blok.
                    $p = $periods->get($periodKey) ?? $latest;
                    $remS = (float) ($p->ipl_security_amount ?? 0);
                    $remG = (float) ($p->ipl_garbage_amount ?? 0);
                    $remK = (float) ($p->ipl_kas_rt_amount ?? 0);
                    $key = '0-' . $periodKey;
                    $isPending = in_array($key, $pendingKeys);
                    $remTotal = $remS + $remG + $remK;
                    $locked = $isPending || $remTotal <= 0;
                    $out->push([
                        'key'          => $key,
                        'year'         => $c->year,
                        'month'        => $c->month,
                        'block_id'     => null,
                        'block_code'   => null,
                        'label'        => $c->translatedFormat('F Y'),
                        'status'       => $isPending ? 'pending' : 'unbilled',
                        'locked'       => $locked,
                        'is_future'    => $isFuture,
                        'rem_security' => $remS,
                        'rem_garbage'  => $remG,
                        'rem_kas_rt'   => $remK,
                        'rem_total'    => $remTotal,
                        'billing_id'   => null,
                    ]);
                }
            } else {
                // Periode lampau tanpa tagihan — lewati, jangan tampilkan.
            }
        }

        // Urut: blok (null di akhir), lalu tahun, lalu bulan.
        return $out->sortBy(function ($item) {
            $blockPart = $item['block_code'] ?? chr(255);
            return $blockPart . sprintf('%04d', $item['year']) . sprintf('%02d', $item['month']);
        })->values();
    }

    private function notifyAdmins($resident, string $type, float $amount, ?string $blockCode): void
    {
        try {
            $admins = User::whereIn('role', ['super_admin', 'admin', 'perumahan', 'pengurus_rt'])->get();
            foreach ($admins as $admin) {
                $admin->notify(new ResidentPaymentSubmitted($resident->name, $type, $amount, $blockCode));
            }
        } catch (\Exception) {}
    }

    // ─── Render ──────────────────────────────────────────────────────────────

    public function render()
    {
        $resident = Auth::guard('resident')->user();

        $billings = IplBilling::with(['period', 'payments', 'chargeItems', 'houseBlock'])
            ->where('responsible_resident_id', $resident->id)
            ->orderByDesc('due_date')
            ->get();

        // Untuk pemilik: tagihan IPL unit yang dikontrakkan (is_ipl_payer = true).
        $ownedBlockIds = $resident->currentAssignments()
            ->where('ownership_type', 'pemilik')
            ->pluck('house_block_id');

        $monitoredBillings = collect();
        if ($ownedBlockIds->isNotEmpty()) {
            $monitoredBillings = IplBilling::with(['period', 'payments', 'houseBlock'])
                ->whereIn('house_block_id', $ownedBlockIds)
                ->where('responsible_resident_id', '!=', $resident->id)
                ->whereNotNull('responsible_resident_id')
                ->orderByDesc('due_date')
                ->get()
                ->load('responsibleResident');
        }

        $allRequests = ResidentPaymentRequest::where('resident_id', $resident->id)
            ->where('type', 'ipl')
            ->with('iplBilling.period')
            ->orderByDesc('created_at')
            ->get();

        $pendingRequests   = $allRequests->where('status', 'pending');
        $pendingBillingIds = $pendingRequests->pluck('ipl_billing_id')->filter()->toArray();

        // Riwayat gabungan: IplPayment (admin) + pengajuan portal non-confirmed.
        $history = collect();
        foreach ($billings as $billing) {
            foreach ($billing->payments as $pay) {
                $total = (float)$pay->amount_security + (float)$pay->amount_garbage + (float)$pay->amount_kas_rt;
                $history->push([
                    'date'        => $pay->payment_date->toDateString(),
                    'period'      => $billing->period
                                     ? Carbon::create($billing->period->year, $billing->period->month)->translatedFormat('F Y')
                                     : '—',
                    'amount'      => $total,
                    'method'      => $pay->payment_method,
                    'reference'   => $pay->reference_number,
                    'notes'       => $pay->notes,
                    'source'      => 'admin',
                    'status'      => 'confirmed',
                    'admin_notes' => null,
                    'breakdown'   => [
                        'security' => (float)$pay->amount_security,
                        'garbage'  => (float)$pay->amount_garbage,
                        'kas_rt'   => (float)$pay->amount_kas_rt,
                    ],
                ]);
            }
        }
        foreach ($allRequests as $req) {
            if ($req->status === 'confirmed') continue;

            $period = null;
            if ($req->iplBilling?->period) {
                $period = Carbon::create($req->iplBilling->period->year, $req->iplBilling->period->month)->translatedFormat('F Y');
            } elseif ($req->period_year && $req->period_month) {
                $period = Carbon::create($req->period_year, $req->period_month)->translatedFormat('F Y');
            } elseif ($req->notes && preg_match('/periode ([A-Za-z]+ \d{4})/', $req->notes, $m)) {
                $period = $m[1];
            }

            $history->push([
                'date'        => $req->created_at->toDateString(),
                'period'      => $period ?? '—',
                'amount'      => (float)$req->amount,
                'method'      => $req->payment_method,
                'reference'   => $req->reference_number,
                'notes'       => $req->notes,
                'source'      => 'portal',
                'status'      => $req->status,
                'admin_notes' => $req->admin_notes,
                'breakdown'   => [
                    'security' => (float)$req->amount_security,
                    'garbage'  => (float)$req->amount_garbage,
                    'kas_rt'   => (float)$req->amount_kas_rt,
                ],
            ]);
        }
        $history = $history->sortByDesc('date')->values();

        // ─── Matrix per-blok tahunan (seperti admin) ───────────────────────
        $selectedYear = $billings->filter(fn($b) => $b->period)
            ->max(fn($b) => $b->period->year) ?: Carbon::now()->year;

        $yearPeriods = IplPeriod::where('year', $selectedYear)->orderBy('month')->get()->keyBy('month');
        $periodIds   = $yearPeriods->pluck('id')->toArray();

        // Semua blok yang pernah/tengah ditempati penghuni ini.
        $billingBlockIds = $billings->pluck('house_block_id')->unique()->values()->toArray();
        $currentBlockIds = $resident->currentAssignments()->pluck('house_block_id')->toArray();
        $allBlockIds     = array_unique(array_merge($billingBlockIds, $currentBlockIds));

        $blocks = HouseBlock::whereIn('id', $allBlockIds)
            ->with('residents')
            ->orderBy('block_letter')->orderBy('unit_number')
            ->get();

        // Tagihan tahun terpilih, di-index [block_id][period_id].
        $yearBillings = IplBilling::with('period')
            ->where('responsible_resident_id', $resident->id)
            ->whereIn('ipl_period_id', $periodIds)
            ->get();

        $billingsMap = [];
        foreach ($yearBillings as $b) {
            $billingsMap[$b->house_block_id][$b->ipl_period_id] = $b;
        }

        $blockMatrix        = [];
        $grandTotalOutstanding = 0;
        $totalUnpaidBlocks     = 0;

        foreach ($blocks as $block) {
            $monthCells      = [];
            $totalOutstanding = 0;
            $unpaidMonths    = [];
            $hasAnyBilling   = false;

            for ($m = 1; $m <= 12; $m++) {
                $period = $yearPeriods->get($m);
                if (!$period) {
                    $monthCells[$m] = ['status' => 'no_period'];
                    continue;
                }
                $billing = $billingsMap[$block->id][$period->id] ?? null;
                if (!$billing) {
                    $monthCells[$m] = ['status' => 'no_billing'];
                    continue;
                }
                $hasAnyBilling = true;
                $status = $billing->status;
                $monthCells[$m] = [
                    'status'      => $status,
                    'outstanding' => $billing->outstanding,
                    'billing_id'  => $billing->id,
                ];
                if ($status !== 'paid') {
                    $totalOutstanding += $billing->outstanding;
                    $unpaidMonths[] = $m;
                }
            }

            if ($totalOutstanding > 0) $totalUnpaidBlocks++;
            $grandTotalOutstanding += $totalOutstanding;

            $blockMatrix[] = [
                'block'            => $block,
                'months'           => $monthCells,
                'totalOutstanding' => $totalOutstanding,
                'unpaidMonths'     => $unpaidMonths,
                'hasAnyBilling'    => $hasAnyBilling,
            ];
        }

        // Checklist bulan (hanya saat modal terbuka) — exclude locked biar @empty di blade berfungsi.
        $payMonths = $this->isChecklistOpen
            ? $this->buildPayMonths($resident)->reject(fn($m) => $m['locked'])
            : collect();

        $monthLabels = [1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun',
                        7 => 'Jul', 8 => 'Agu', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'];

        return view('livewire.penghuni.ipl-portal', compact(
            'resident', 'billings', 'pendingRequests', 'pendingBillingIds',
            'history', 'payMonths', 'blockMatrix', 'selectedYear',
            'grandTotalOutstanding', 'totalUnpaidBlocks', 'monthLabels', 'yearPeriods',
            'monitoredBillings'
        ));
    }
}
