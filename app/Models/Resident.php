<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Resident extends Model
{
    use HasFactory;

    protected $fillable = [
        'house_block_id', 'name', 'nik', 'phone', 'whatsapp', 'email',
        'ownership_status', 'occupancy_status', 'resident_since', 'notes', 'is_active',
    ];

    protected $casts = [
        'nik' => 'encrypted',
        'resident_since' => 'date',
        'is_active' => 'boolean',
    ];

    public function houseBlock(): BelongsTo
    {
        return $this->belongsTo(HouseBlock::class);
    }

    public function iplBillings(): HasMany
    {
        return $this->hasMany(IplBilling::class);
    }

    public function iplPayments(): HasManyThrough
    {
        return $this->hasManyThrough(IplPayment::class, IplBilling::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
