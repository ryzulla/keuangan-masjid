<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmergencyAlert extends Model
{
    protected $fillable = [
        'resident_id', 'block_code', 'message', 'is_active',
        'stopped_by', 'stopped_at',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'stopped_at' => 'datetime',
    ];

    public function resident(): BelongsTo
    {
        return $this->belongsTo(Resident::class);
    }

    public function stopper(): BelongsTo
    {
        return $this->belongsTo(Resident::class, 'stopped_by');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function stop(?int $userId = null): void
    {
        $this->update([
            'is_active'  => false,
            'stopped_by' => $userId,
            'stopped_at' => now(),
        ]);
    }
}
