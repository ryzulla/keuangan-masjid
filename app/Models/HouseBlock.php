<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HouseBlock extends Model
{
    use HasFactory;

    protected $fillable = [
        'block_letter', 'unit_number', 'is_active', 'notes',
        'is_for_rent', 'listing_type', 'rental_price', 'rental_description', 'rental_duration',
        'land_area', 'building_area', 'water_source', 'electricity', 'bedrooms', 'bathrooms', 'garage',
    ];
    protected $casts = [
        'is_active'    => 'boolean',
        'is_for_rent'  => 'boolean',
        'rental_price' => 'decimal:2',
        'land_area'    => 'decimal:2',
        'building_area'=> 'decimal:2',
        'electricity'  => 'integer',
        'bedrooms'     => 'integer',
        'bathrooms'    => 'integer',
        'garage'       => 'integer',
    ];
    protected $appends = ['block_code'];

    public function getBlockCodeAttribute(): string
    {
        return $this->block_letter . '-' . $this->unit_number;
    }

    public function getRentalDurationLabelAttribute(): ?string
    {
        return match($this->rental_duration) {
            'bulanan'  => 'Bulanan',
            '6bulan'   => '6 Bulan',
            'tahunan'  => 'Tahunan',
            default    => null,
        };
    }

    public function getWaterSourceLabelAttribute(): ?string
    {
        return match($this->water_source) {
            'pdam'  => 'Air PDAM',
            'tanah' => 'Air Tanah',
            'both'  => 'PDAM + Tanah',
            default => null,
        };
    }

    public function getListingTypeLabelAttribute(): ?string
    {
        return match($this->listing_type) {
            'sewa' => 'Disewakan',
            'jual' => 'Dijual',
            default => null,
        };
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

    public function photos(): HasMany
    {
        return $this->hasMany(HouseBlockPhoto::class)->orderBy('sort_order');
    }

    public function getPrimaryPhotoAttribute(): ?HouseBlockPhoto
    {
        return $this->photos()->where('is_primary', true)->first()
            ?? $this->photos()->first();
    }

    public function getOwnerContactAttribute(): ?array
    {
        $owner = $this->owners()->first();
        if (!$owner) return null;
        return [
            'name'     => $owner->name,
            'phone'    => $owner->phone,
            'whatsapp' => $owner->whatsapp,
            'email'    => $owner->email,
        ];
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
