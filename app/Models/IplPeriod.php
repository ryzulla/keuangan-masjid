<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\IplPeriodTariffRate;

class IplPeriod extends Model
{
    use HasFactory;

    protected $fillable = [
        'year', 'month', 'ipl_security_amount', 'ipl_garbage_amount', 'ipl_kas_rt_amount', 'notes',
    ];

    protected $casts = [
        'ipl_security_amount' => 'decimal:2',
        'ipl_garbage_amount' => 'decimal:2',
        'ipl_kas_rt_amount' => 'decimal:2',
    ];

    protected $appends = ['period_label'];

    public function getPeriodLabelAttribute(): string
    {
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];
        return ($months[$this->month] ?? $this->month) . ' ' . $this->year;
    }

    public function billings(): HasMany
    {
        return $this->hasMany(IplBilling::class);
    }

    public function tariffRates(): HasMany
    {
        return $this->hasMany(IplPeriodTariffRate::class);
    }

    public function extraTariffRates(): HasMany
    {
        return $this->hasMany(IplPeriodTariffRate::class)
            ->whereHas('tariffType', fn($q) => $q->whereNull('billing_key'));
    }
}
