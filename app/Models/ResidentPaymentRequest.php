<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResidentPaymentRequest extends Model
{
    protected $fillable = [
        'resident_id', 'type', 'ipl_billing_id', 'period_year', 'period_month', 'campaign_id',
        'amount', 'amount_security', 'amount_garbage', 'amount_kas_rt',
        'donor_name', 'donor_type', 'payment_method', 'bank_name',
        'reference_number', 'proof_photo', 'notes',
        'donation_form', 'donation_type',
        'status', 'admin_notes', 'confirmed_by', 'confirmed_at',
    ];

    protected $casts = [
        'amount'          => 'decimal:2',
        'amount_security' => 'decimal:2',
        'amount_garbage'  => 'decimal:2',
        'amount_kas_rt'   => 'decimal:2',
        'confirmed_at'    => 'datetime',
    ];

    public function resident(): BelongsTo
    {
        return $this->belongsTo(Resident::class);
    }

    public function iplBilling(): BelongsTo
    {
        return $this->belongsTo(IplBilling::class, 'ipl_billing_id');
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function confirmedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'confirmed_by');
    }
}
