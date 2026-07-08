<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FamilyMember extends Model
{
    protected $fillable = ['resident_id', 'name', 'photo', 'relationship', 'gender', 'nik', 'birth_date', 'notes', 'sort_order'];

    protected $casts = [
        'nik'        => 'encrypted',
        'birth_date' => 'date',
    ];

    public function resident(): BelongsTo
    {
        return $this->belongsTo(Resident::class);
    }

    public function getRelationshipLabelAttribute(): string
    {
        return match ($this->relationship) {
            'istri'     => 'Istri',
            'suami'     => 'Suami',
            'anak'      => 'Anak',
            'orang_tua' => 'Orang Tua',
            'mertua'    => 'Mertua',
            'saudara'   => 'Saudara',
            default     => 'Lainnya',
        };
    }
}
