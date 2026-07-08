<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use App\Models\ResidentPaymentRequest;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'organization_type', 'source_account_id',
        'description', 'content', 'location', 'video_url',
        'target_amount', 'start_date', 'end_date', 'status', 'image',
    ];

    protected $casts = [
        'target_amount' => 'decimal:2',
        'start_date'    => 'date',
        'end_date'      => 'date',
    ];

    public function sourceAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'source_account_id');
    }

    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(CampaignPhoto::class)->orderBy('sort_order');
    }

    public function residentPaymentRequests(): HasMany
    {
        return $this->hasMany(ResidentPaymentRequest::class);
    }

    public function transactions(): HasManyThrough
    {
        return $this->hasManyThrough(
            Transaction::class,
            Donation::class,
            'campaign_id',
            'id',
            'id',
            'transaction_id'
        );
    }

    public function scopeByOrg($query, string $type)
    {
        return $query->where('organization_type', $type);
    }
}
