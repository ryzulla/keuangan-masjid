<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HouseBlock extends Model
{
    use HasFactory;

    protected $fillable = ['block_letter', 'unit_number', 'is_active', 'notes'];
    protected $casts = ['is_active' => 'boolean'];
    protected $appends = ['block_code'];

    public function getBlockCodeAttribute(): string
    {
        return $this->block_letter . '-' . $this->unit_number;
    }

    public function residents(): BelongsToMany
    {
        return $this->belongsToMany(Resident::class, 'resident_house_blocks')
            ->withPivot('ownership_type', 'occupancy_status', 'resident_since', 'is_primary_residence', 'notes')
            ->withTimestamps()
            ->whereNull('resident_house_blocks.ended_at')
            ->where('residents.is_active', true);
    }

    public function occupants(): BelongsToMany
    {
        return $this->belongsToMany(Resident::class, 'resident_house_blocks')
            ->withPivot('ownership_type', 'occupancy_status', 'resident_since', 'is_primary_residence', 'notes')
            ->withTimestamps()
            ->whereNull('resident_house_blocks.ended_at')
            ->wherePivot('occupancy_status', 'dihuni')
            ->where('residents.is_active', true);
    }

    public function owners(): BelongsToMany
    {
        return $this->belongsToMany(Resident::class, 'resident_house_blocks')
            ->withPivot('ownership_type', 'occupancy_status', 'resident_since', 'is_primary_residence', 'notes')
            ->withTimestamps()
            ->whereNull('resident_house_blocks.ended_at')
            ->wherePivot('ownership_type', 'pemilik')
            ->where('residents.is_active', true);
    }

    // All resident_house_block records (including history)
    public function assignments(): HasMany
    {
        return $this->hasMany(ResidentHouseBlock::class);
    }

    // Only active (current) assignments — penghuni nonaktif tidak dihitung aktif
    public function currentAssignments(): HasMany
    {
        return $this->hasMany(ResidentHouseBlock::class)
            ->whereNull('ended_at')
            ->whereHas('resident', fn($q) => $q->where('is_active', true))
            ->with('resident');
    }

    // Shortcut: current owner record
    public function ownerAssignment(): HasMany
    {
        return $this->hasMany(ResidentHouseBlock::class)
            ->whereNull('ended_at')->where('ownership_type', 'pemilik')
            ->whereHas('resident', fn($q) => $q->where('is_active', true));
    }

    // Shortcut: current tenant record (kontrak/kos)
    public function tenantAssignment(): HasMany
    {
        return $this->hasMany(ResidentHouseBlock::class)
            ->whereNull('ended_at')->whereIn('ownership_type', ['kontrak', 'kos'])
            ->whereHas('resident', fn($q) => $q->where('is_active', true));
    }

    public function iplBillings(): HasMany
    {
        return $this->hasMany(IplBilling::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public static function generateAll(): void
    {
        foreach (range('A', 'P') as $letter) {
            foreach (range(1, 9) as $number) {
                self::firstOrCreate(
                    ['block_letter' => $letter, 'unit_number' => $number],
                    ['is_active' => true]
                );
            }
        }
    }
}
