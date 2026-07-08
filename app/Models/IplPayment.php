<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IplPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'ipl_billing_id', 'payment_date', 'amount_security', 'amount_garbage', 'amount_kas_rt',
        'payment_method', 'account_id', 'reference_number', 'received_by', 'notes', 'user_id',
        'extra_charges_paid',
    ];

    protected $casts = [
        'payment_date'       => 'date',
        'amount_security'    => 'decimal:2',
        'amount_garbage'     => 'decimal:2',
        'amount_kas_rt'      => 'decimal:2',
        'extra_charges_paid' => 'array',
    ];

    protected static function booted(): void
    {
        static::saved(function (IplPayment $payment) {
            $payment->billing->updateStatus();
        });

        static::deleted(function (IplPayment $payment) {
            $payment->billing->updateStatus();
        });
    }

    public function billing(): BelongsTo
    {
        return $this->belongsTo(IplBilling::class, 'ipl_billing_id');
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
