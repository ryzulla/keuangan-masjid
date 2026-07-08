<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IplTariffType extends Model
{
    protected $fillable = [
        'name', 'description', 'billing_key',
        'default_amount', 'default_account_id', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'default_amount' => 'decimal:2',
        'is_active'      => 'boolean',
        'sort_order'     => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeExtra($query)
    {
        return $query->whereNull('billing_key');
    }

    public function scopeSystem($query)
    {
        return $query->whereNotNull('billing_key');
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'default_account_id');
    }

    public function periodRates(): HasMany
    {
        return $this->hasMany(IplPeriodTariffRate::class);
    }

    public function billingChargeItems(): HasMany
    {
        return $this->hasMany(IplBillingChargeItem::class);
    }
}
