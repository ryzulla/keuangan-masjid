<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IplBillingChargeItem extends Model
{
    protected $fillable = ['ipl_billing_id', 'ipl_tariff_type_id', 'billed_amount', 'paid_amount'];

    protected $casts = [
        'billed_amount' => 'decimal:2',
        'paid_amount'   => 'decimal:2',
    ];

    public function billing(): BelongsTo
    {
        return $this->belongsTo(IplBilling::class, 'ipl_billing_id');
    }

    public function tariffType(): BelongsTo
    {
        return $this->belongsTo(IplTariffType::class, 'ipl_tariff_type_id');
    }
}
