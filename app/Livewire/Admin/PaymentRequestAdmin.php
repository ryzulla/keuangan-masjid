<?php
namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use App\Models\ResidentPaymentRequest;
use App\Models\IplPayment;
use App\Models\IplPeriod;
use App\Models\IplBilling;
use App\Models\Category;
use App\Models\Transaction;
use App\Support\PaymentAccounts;
use App\Support\IplLedger;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Notifications\ResidentPaymentConfirmed;
use App\Notifications\ResidentPaymentRejected;

#[Layout('layouts.app')]
class PaymentRequestAdmin extends Component
{
    use WithPagination;

    public string $filterType   = '';
    public string $filterStatus = 'pending';

    public bool   $isConfirmModalOpen = false;
    public ?int   $confirmingId       = null;
    public string $confirmingType     = '';   // 'ipl' | 'donation'
    public string $confirmingOrg      = 'perumahan'; // organisasi tujuan: 'perumahan' | 'dkm'
    public string $adminNotes         = '';

    /** Detail permintaan yang sedang dikonfirmasi (untuk ditampilkan di modal). */
    #[Computed]
    public function confirmingRequest()
    {
        if (!$this->confirmingId) return null;
        return ResidentPaymentRequest::with([
            'resident', 'iplBilling.period', 'iplBilling.houseBlock', 'campaign',
        ])->find($this->confirmingId);
    }

    public function confirmModal(int $id): void
    {
        $req = ResidentPaymentRequest::with('campaign')->find($id);

        $this->confirmingId       = $id;
        $this->confirmingType     = $req?->type ?? '';
        // IPL selalu ke Perumahan. Donasi mengikuti organisasi program/campaign-nya
        // (donasi tanpa campaign default ke DKM).
        $this->confirmingOrg      = $req?->type === 'donation'
            ? ($req?->campaign?->organization_type ?? 'dkm')
            : 'perumahan';
        $this->adminNotes         = '';
        $this->isConfirmModalOpen = true;
    }

    public function confirm(): void
    {
        $req = ResidentPaymentRequest::with(['iplBilling.chargeItems', 'iplBilling.period', 'iplBilling.houseBlock', 'campaign', 'resident'])
            ->findOrFail($this->confirmingId);

        // Cegah double-konfirmasi (posting ledger ganda) bila sudah diproses.
        if ($req->status !== 'pending') {
            $this->isConfirmModalOpen = false;
            session()->flash('error', 'Permintaan ini sudah diproses sebelumnya.');
            return;
        }

        $this->validate([
            'adminNotes' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $amtSecurity = 0.0;
            $amtGarbage  = 0.0;
            $amtKasRt    = 0.0;

            // ── IPL dengan tagihan terdaftar ──────────────────────────────
            if ($req->type === 'ipl' && $req->ipl_billing_id) {
                $billing     = $req->iplBilling;
                $outstanding = $billing->outstanding;

                $remSecurity = max(0, (float)$billing->ipl_security_amount - (float)$billing->paid_security);
                $remGarbage  = max(0, (float)$billing->ipl_garbage_amount  - (float)$billing->paid_garbage);
                $remKasRt    = max(0, (float)$billing->ipl_kas_rt_amount   - (float)$billing->paid_kas_rt);

                $amtSecurity = min($outstanding, $remSecurity);
                $amtGarbage  = min(max(0, $outstanding - $amtSecurity), $remGarbage);
                $amtKasRt    = min(max(0, $outstanding - $amtSecurity - $amtGarbage), $remKasRt);

                // Posting ke buku besar per komponen ke akun tujuan masing-masing.
                $period = $billing->period
                    ? Carbon::create($billing->period->year, $billing->period->month)->translatedFormat('F Y')
                    : '';
                $block = $billing->houseBlock?->block_code ?? '';
                $desc  = trim("IPL {$period} {$block} — {$req->resident->name}");

                $primary = IplLedger::record(
                    $amtSecurity, $amtGarbage, $amtKasRt, [],
                    $desc, now()->toDateString(), auth()->id()
                );

                IplPayment::create([
                    'ipl_billing_id'   => $billing->id,
                    'payment_date'     => now()->toDateString(),
                    'amount_security'  => $amtSecurity,
                    'amount_garbage'   => $amtGarbage,
                    'amount_kas_rt'    => $amtKasRt,
                    'payment_method'   => $req->payment_method,
                    'account_id'       => $primary,
                    'reference_number' => $req->reference_number,
                    'received_by'      => auth()->user()->name,
                    'notes'            => 'Dikonfirmasi dari portal penghuni. ' . $req->notes,
                    'user_id'          => auth()->id(),
                ]);
            }

            // ── IPL direct pay (tanpa tagihan) ────────────────────────────
            elseif ($req->type === 'ipl' && !$req->ipl_billing_id) {
                $this->createIplDirectTransaction($req);
            }

            // ── Donasi dari portal ────────────────────────────────────────
            elseif ($req->type === 'donation') {
                $this->createDonationTransaction($req);
            }

            $req->update([
                'status'       => 'confirmed',
                'admin_notes'  => $this->adminNotes ?: null,
                'confirmed_by' => auth()->id(),
                'confirmed_at' => now(),
            ]);

            DB::commit();
            $this->isConfirmModalOpen = false;
            $this->confirmingId       = null;
            $this->confirmingType     = '';
            $this->adminNotes         = '';

            try {
                $periodLabel = $req->iplBilling?->period
                    ? Carbon::create($req->iplBilling->period->year, $req->iplBilling->period->month)->translatedFormat('F Y')
                    : null;
                if ($req->resident?->wantsNotification('payment_status')) {
                    $req->resident->notify(new ResidentPaymentConfirmed(
                        $req->type,
                        (float) $req->amount,
                        $periodLabel,
                        $this->adminNotes ?: null,
                    ));
                }
            } catch (\Exception) {}

            session()->flash('success', 'Pembayaran berhasil dikonfirmasi dan dicatat ke Buku Besar.');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Gagal: ' . $e->getMessage());
        }
    }

    // ── Helper: IPL direct pay (tanpa tagihan) → buat billing lunas ───────

    private function createIplDirectTransaction(ResidentPaymentRequest $req): void
    {
        // Tentukan periode: kolom eksplisit, atau parse dari notes (bulan Indonesia).
        $year  = $req->period_year;
        $month = $req->period_month;

        $periodLabel = '';
        if ((! $year || ! $month) && $req->notes && preg_match('/periode ([A-Za-z]+ \d{4})/', $req->notes, $m)) {
            $periodLabel = $m[1];
            [$parsedYear, $parsedMonth] = $this->parseIndoPeriod($m[1]);
            $year  = $year  ?: $parsedYear;
            $month = $month ?: $parsedMonth;
        }

        $desc = trim("Pembayaran IPL {$periodLabel} — {$req->resident->name}");

        // Periode tidak dapat ditentukan: posting lump-sum ke buku besar saja.
        if (! $year || ! $month) {
            IplLedger::record((float) $req->amount, 0, 0, [], $desc, now()->toDateString(), auth()->id());
            return;
        }

        $year  = (int) $year;
        $month = (int) $month;

        // Buat periode & tagihan "bayar di muka" supaya generate tagihan tidak dobel.
        $ref = IplPeriod::latest('year')->latest('month')->first();

        $period = IplPeriod::firstOrCreate(
            ['year' => $year, 'month' => $month],
            [
                'ipl_security_amount' => $ref?->ipl_security_amount ?? 0,
                'ipl_garbage_amount'  => $ref?->ipl_garbage_amount ?? 0,
                'ipl_kas_rt_amount'   => $ref?->ipl_kas_rt_amount ?? 0,
            ]
        );

        $blockId = $req->resident->currentAssignments()->first()?->house_block_id;

        $billing = IplBilling::firstOrCreate(
            ['ipl_period_id' => $period->id, 'house_block_id' => $blockId],
            [
                'responsible_resident_id' => $req->resident_id,
                'ipl_security_amount'     => $period->ipl_security_amount,
                'ipl_garbage_amount'      => $period->ipl_garbage_amount,
                'ipl_kas_rt_amount'       => $period->ipl_kas_rt_amount,
                'paid_security'           => 0,
                'paid_garbage'            => 0,
                'paid_kas_rt'             => 0,
                'status'                  => 'unpaid',
                'due_date'                => Carbon::create($year, $month, 10)->toDateString(),
            ]
        );

        // Bagi jumlah bayar ke komponen: security dulu, sisa garbage, sisa kas_rt.
        $amount      = (float) $req->amount;
        $remSecurity = max(0, (float) $billing->ipl_security_amount - (float) $billing->paid_security);
        $remGarbage  = max(0, (float) $billing->ipl_garbage_amount  - (float) $billing->paid_garbage);
        $remKasRt    = max(0, (float) $billing->ipl_kas_rt_amount   - (float) $billing->paid_kas_rt);

        $amtSecurity = min($amount, $remSecurity);
        $amtGarbage  = min(max(0, $amount - $amtSecurity), $remGarbage);
        $amtKasRt    = min(max(0, $amount - $amtSecurity - $amtGarbage), $remKasRt);

        $primary = IplLedger::record(
            $amtSecurity, $amtGarbage, $amtKasRt, [],
            $desc, now()->toDateString(), auth()->id()
        );

        // IplPayment.saved → billing->updateStatus() → jadi paid/partial.
        IplPayment::create([
            'ipl_billing_id'   => $billing->id,
            'payment_date'     => now()->toDateString(),
            'amount_security'  => $amtSecurity,
            'amount_garbage'   => $amtGarbage,
            'amount_kas_rt'    => $amtKasRt,
            'payment_method'   => $req->payment_method,
            'account_id'       => $primary,
            'reference_number' => $req->reference_number,
            'received_by'      => auth()->user()->name,
            'notes'            => 'Dikonfirmasi dari portal penghuni (bayar di muka). ' . $req->notes,
            'user_id'          => auth()->id(),
        ]);

        // Tautkan pengajuan ke tagihan yang baru dibuat agar deteksi "sudah dibayar"
        // & riwayat bekerja konsisten (bayar langsung tidak lagi kehilangan tautan).
        $req->forceFill(['ipl_billing_id' => $billing->id])->save();
    }

    /** Parse "Juli 2026" (bulan Indonesia) → [year, month]; null bila gagal. */
    private function parseIndoPeriod(string $label): array
    {
        $months = [
            'januari' => 1, 'februari' => 2, 'maret' => 3, 'april' => 4,
            'mei' => 5, 'juni' => 6, 'juli' => 7, 'agustus' => 8,
            'september' => 9, 'oktober' => 10, 'november' => 11, 'desember' => 12,
        ];
        if (preg_match('/([A-Za-z]+)\s+(\d{4})/', $label, $m)) {
            $mo = $months[strtolower($m[1])] ?? null;
            return $mo ? [(int) $m[2], $mo] : [null, null];
        }
        return [null, null];
    }

    private function createDonationTransaction(ResidentPaymentRequest $req): void
    {
        // Organisasi tujuan mengikuti program/campaign donasi. Donasi program perumahan
        // dicatat sebagai pendapatan Perumahan; donasi DKM sebagai pemasukan DKM.
        $org = $req->campaign?->organization_type ?? 'dkm';

        if ($org === 'perumahan') {
            $categoryId = $this->categoryId('Donasi Perumahan', 'perumahan')
                ?? $this->categoryId('Pendapatan Lain-lain Perumahan', 'perumahan');
        } else {
            $categoryId = $req->campaign_id
                ? ($this->categoryId('Donasi Program', 'dkm') ?? $this->categoryId('Infaq & Sedekah Umum', 'dkm'))
                : $this->categoryId('Infaq & Sedekah Umum', 'dkm');
        }

        $desc = "Donasi dari {$req->resident->name}";
        if ($req->campaign) {
            $desc .= " — {$req->campaign->name}";
        }

        $accountId = PaymentAccounts::donation($org);

        $tx = Transaction::create([
            'type'             => 'debit',
            'amount'           => (float) $req->amount,
            'account_id'       => $accountId,
            'category_id'      => $categoryId,
            'description'      => $desc,
            'transaction_date' => now()->toDateString(),
            'user_id'          => auth()->id(),
            'campaign_id'      => $req->campaign_id,
        ]);

        $tx->donation()->create([
            'donor_name'  => $req->donor_name ?? $req->resident->name,
            'donor_type'  => 'warga',
            'resident_id' => $req->resident_id,
            'campaign_id' => $req->campaign_id,
            'type'        => $org === 'dkm' ? 'infaq' : 'umum',
        ]);
    }

    private function categoryId(string $name, string $org): ?int
    {
        return Category::where('name', $name)
            ->where('organization_type', $org)
            ->where('type', 'income')
            ->value('id');
    }

    // ── Reject ───────────────────────────────────────────────────────────

    public function reject(int $id, string $reason = ''): void
    {
        $req = ResidentPaymentRequest::with('resident')->findOrFail($id);
        if ($req->status !== 'pending') {
            session()->flash('error', 'Permintaan ini sudah diproses sebelumnya.');
            return;
        }
        $req->update([
            'status'       => 'rejected',
            'admin_notes'  => $reason ?: 'Ditolak oleh admin.',
            'confirmed_by' => auth()->id(),
            'confirmed_at' => now(),
        ]);

        try {
            if ($req->resident?->wantsNotification('payment_status')) {
                $req->resident->notify(new ResidentPaymentRejected(
                    $req->type,
                    (float) $req->amount,
                    $reason ?: null,
                ));
            }
        } catch (\Exception) {}

        session()->flash('success', 'Permintaan pembayaran ditolak.');
    }

    public function render()
    {
        $requests = ResidentPaymentRequest::with(['resident', 'iplBilling.period', 'iplBilling.houseBlock', 'campaign'])
            ->when($this->filterType, fn($q) => $q->where('type', $this->filterType))
            ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
            ->orderByDesc('created_at')
            ->paginate(15);

        $pendingCount = ResidentPaymentRequest::where('status', 'pending')->count();

        return view('livewire.admin.payment-request-admin', compact(
            'requests', 'pendingCount'
        ));
    }
}
