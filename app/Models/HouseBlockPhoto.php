<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HouseBlockPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'house_block_id', 'photo_path', 'caption', 'sort_order', 'is_primary',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_primary' => 'boolean',
    ];

    public function houseBlock(): BelongsTo
    {
        return $this->belongsTo(HouseBlock::class);
    }
}
