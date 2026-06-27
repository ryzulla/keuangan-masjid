<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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

    public function residents(): HasMany
    {
        return $this->hasMany(Resident::class);
    }

    public function activeResident()
    {
        return $this->hasOne(Resident::class)->where('is_active', true)->latest();
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
        $letters = range('A', 'P');
        foreach ($letters as $letter) {
            for ($unit = 1; $unit <= 9; $unit++) {
                self::firstOrCreate(
                    ['block_letter' => $letter, 'unit_number' => $unit],
                    ['is_active' => true]
                );
            }
        }
    }
}
