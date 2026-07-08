<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class IplBilling extends Model
{
    use HasFactory;

    protected $fillable = [
        'ipl_period_id', 'house_block_id', 'responsible_resident_id',
        'ipl_security_amount', 'ipl_garbage_amount', 'ipl_kas_rt_amount',
        'paid_security', 'paid_garbage', 'paid_kas_rt',
        'waived_security', 'waived_garbage', 'waived_kas_rt',
        'waiver_reason', 'waived_by', 'waived_at',
        'status', 'due_date', 'notes',
    ];

    protected $casts = [
        'ipl_security_amount' => 'decimal:2',
        'ipl_garbage_amount' => 'decimal:2',
        'ipl_kas_rt_amount' => 'decimal:2',
        'paid_security' => 'decimal:2',
        'paid_garbage' => 'decimal:2',
        'paid_kas_rt' => 'decimal:2',
        'waived_security' => 'decimal:2',
        'waived_garbage' => 'decimal:2',
        'waived_kas_rt' => 'decimal:2',
        'waived_at' => 'datetime',
        'due_date' => 'date',
    ];

    protected $appends = ['total_amount', 'total_paid', 'total_waived', 'outstanding'];

    public function getTotalAmountAttribute(): float
    {
        $base  = (float)$this->ipl_security_amount + (float)$this->ipl_garbage_amount + (float)$this->ipl_kas_rt_amount;
        $extra = $this->relationLoaded('chargeItems')
            ? $this->chargeItems->sum('billed_amount')
            : $this->chargeItems()->sum('billed_amount');
        return $base + (float)$extra;
    }

    public function getTotalPaidAttribute(): float
    {
        $base  = (float)$this->paid_security + (float)$this->paid_garbage + (float)$this->paid_kas_rt;
        $extra = $this->relationLoaded('chargeItems')
            ? $this->chargeItems->sum('paid_amount')
            : $this->chargeItems()->sum('paid_amount');
        return $base + (float)$extra;
    }

    public function getTotalWaivedAttribute(): float
    {
        // Pemutihan hanya berlaku pada 3 komponen inti (bukan iuran tambahan).
        return (float)$this->waived_security + (float)$this->waived_garbage + (float)$this->waived_kas_rt;
    }

    public function getOutstandingAttribute(): float
    {
        // Sisa = tagihan − dibayar − dibebaskan (pemutihan dianggap lunas, tanpa kas).
        return max(0, $this->total_amount - $this->total_paid - $this->total_waived);
    }

    /** Sisa per komponen setelah dibayar & dibebaskan. */
    public function remainingSecurity(): float
    {
        return max(0, (float)$this->ipl_security_amount - (float)$this->paid_security - (float)$this->waived_security);
    }
    public function remainingGarbage(): float
    {
        return max(0, (float)$this->ipl_garbage_amount - (float)$this->paid_garbage - (float)$this->waived_garbage);
    }
    public function remainingKasRt(): float
    {
        return max(0, (float)$this->ipl_kas_rt_amount - (float)$this->paid_kas_rt - (float)$this->waived_kas_rt);
    }

    /** Ada komponen yang dibebaskan (untuk label "Dibebaskan"). */
    public function getIsWaivedAttribute(): bool
    {
        return $this->total_waived > 0;
    }

    public function period(): BelongsTo
    {
        return $this->belongsTo(IplPeriod::class, 'ipl_period_id');
    }

    public function responsibleResident(): BelongsTo
    {
        return $this->belongsTo(Resident::class, 'responsible_resident_id');
    }

    public function houseBlock(): BelongsTo
    {
        return $this->belongsTo(HouseBlock::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(IplPayment::class);
    }

    public function chargeItems(): HasMany
    {
        return $this->hasMany(IplBillingChargeItem::class);
    }

    public function updateStatus(): void
    {
        $totalPaidSecurity = $this->payments()->sum('amount_security');
        $totalPaidGarbage  = $this->payments()->sum('amount_garbage');
        $totalPaidKasRt    = $this->payments()->sum('amount_kas_rt');

        // Recalculate extra charge paid amounts from full payment history
        $extraPaidByType = [];
        foreach ($this->payments()->whereNotNull('extra_charges_paid')->get() as $payment) {
            foreach (($payment->extra_charges_paid ?? []) as $typeId => $amount) {
                $extraPaidByType[$typeId] = ($extraPaidByType[$typeId] ?? 0) + (float)$amount;
            }
        }

        // Sync paid_amount on each charge item
        foreach ($this->chargeItems()->get() as $item) {
            $item->paid_amount = $extraPaidByType[$item->ipl_tariff_type_id] ?? 0;
            $item->saveQuietly();
        }

        $this->paid_security = $totalPaidSecurity;
        $this->paid_garbage  = $totalPaidGarbage;
        $this->paid_kas_rt   = $totalPaidKasRt;

        $totalBilled = (float)$this->ipl_security_amount + (float)$this->ipl_garbage_amount
            + (float)$this->ipl_kas_rt_amount
            + (float)$this->chargeItems()->sum('billed_amount');
        $totalPaid   = (float)$totalPaidSecurity + (float)$totalPaidGarbage + (float)$totalPaidKasRt
            + array_sum($extraPaidByType);
        // Dibebaskan (pemutihan) dihitung sebagai penyelesaian, tapi bukan kas.
        $totalWaived = (float)$this->waived_security + (float)$this->waived_garbage + (float)$this->waived_kas_rt;
        $totalSettled = $totalPaid + $totalWaived;

        $this->status = $totalSettled <= 0 ? 'unpaid'
            : ($totalSettled >= $totalBilled ? 'paid' : 'partial');

        $this->saveQuietly();
    }
}
