<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IplPeriodTariffRate extends Model
{
    protected $fillable = ['ipl_period_id', 'ipl_tariff_type_id', 'amount'];

    protected $casts = ['amount' => 'decimal:2'];

    public function period(): BelongsTo
    {
        return $this->belongsTo(IplPeriod::class, 'ipl_period_id');
    }

    public function tariffType(): BelongsTo
    {
        return $this->belongsTo(IplTariffType::class, 'ipl_tariff_type_id');
    }
}
