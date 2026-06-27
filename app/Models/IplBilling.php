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
        'ipl_period_id', 'resident_id', 'house_block_id',
        'ipl_security_amount', 'ipl_garbage_amount',
        'paid_security', 'paid_garbage',
        'status', 'due_date', 'notes',
    ];

    protected $casts = [
        'ipl_security_amount' => 'decimal:2',
        'ipl_garbage_amount' => 'decimal:2',
        'paid_security' => 'decimal:2',
        'paid_garbage' => 'decimal:2',
        'due_date' => 'date',
    ];

    protected $appends = ['total_amount', 'total_paid', 'outstanding'];

    public function getTotalAmountAttribute(): float
    {
        return (float)$this->ipl_security_amount + (float)$this->ipl_garbage_amount;
    }

    public function getTotalPaidAttribute(): float
    {
        return (float)$this->paid_security + (float)$this->paid_garbage;
    }

    public function getOutstandingAttribute(): float
    {
        return max(0, $this->total_amount - $this->total_paid);
    }

    public function period(): BelongsTo
    {
        return $this->belongsTo(IplPeriod::class, 'ipl_period_id');
    }

    public function resident(): BelongsTo
    {
        return $this->belongsTo(Resident::class);
    }

    public function houseBlock(): BelongsTo
    {
        return $this->belongsTo(HouseBlock::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(IplPayment::class);
    }

    public function updateStatus(): void
    {
        $totalPaidSecurity = $this->payments()->sum('amount_security');
        $totalPaidGarbage = $this->payments()->sum('amount_garbage');

        $this->paid_security = $totalPaidSecurity;
        $this->paid_garbage = $totalPaidGarbage;

        $totalBilled = (float)$this->ipl_security_amount + (float)$this->ipl_garbage_amount;
        $totalPaid = (float)$totalPaidSecurity + (float)$totalPaidGarbage;

        if ($totalPaid <= 0) {
            $this->status = 'unpaid';
        } elseif ($totalPaid >= $totalBilled) {
            $this->status = 'paid';
        } else {
            $this->status = 'partial';
        }

        $this->saveQuietly();
    }
}
