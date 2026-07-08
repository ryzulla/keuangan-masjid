<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResidentHouseBlock extends Model
{
    protected $table = 'resident_house_blocks';

    protected $fillable = [
        'resident_id', 'house_block_id', 'ownership_type', 'occupancy_status',
        'resident_since', 'contract_start_date', 'contract_end_date',
        'monthly_rent', 'is_primary_residence', 'is_ipl_payer', 'notes', 'ended_at',
    ];

    protected $casts = [
        'resident_since'       => 'date',
        'contract_start_date'  => 'date',
        'contract_end_date'    => 'date',
        'monthly_rent'         => 'decimal:2',
        'is_primary_residence' => 'boolean',
        'is_ipl_payer'         => 'boolean',
        'ended_at'             => 'datetime',
    ];

    public function resident(): BelongsTo
    {
        return $this->belongsTo(Resident::class);
    }

    public function houseBlock(): BelongsTo
    {
        return $this->belongsTo(HouseBlock::class);
    }

    public function scopeCurrent($query)
    {
        return $query->whereNull('ended_at');
    }

    public function scopeHistorical($query)
    {
        return $query->whereNotNull('ended_at');
    }

    public function getIsCurrentAttribute(): bool
    {
        if (!is_null($this->ended_at)) {
            return false;
        }
        if ($this->relationLoaded('resident') && $this->resident) {
            return $this->resident->is_active;
        }
        return true;
    }

    public function getOwnershipLabelAttribute(): string
    {
        return match ($this->ownership_type) {
            'pemilik' => 'Pemilik',
            'kontrak' => 'Penyewa Kontrak',
            'kos'     => 'Kos',
            default   => ucfirst($this->ownership_type),
        };
    }

    public function getContractPeriodLabelAttribute(): string
    {
        if (!in_array($this->ownership_type, ['kontrak', 'kos'])) return '';
        $start = $this->contract_start_date?->format('M Y') ?? '?';
        $end   = $this->contract_end_date?->format('M Y')   ?? 'Sekarang';
        return $start . ' – ' . $end;
    }
}
