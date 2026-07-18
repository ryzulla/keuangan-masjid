<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CitizenReport extends Model
{
    protected $fillable = [
        'resident_id', 'category', 'report_for', 'person_name',
        'description', 'status', 'notice_id', 'published_by',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function resident(): BelongsTo
    {
        return $this->belongsTo(Resident::class);
    }

    public function notice(): BelongsTo
    {
        return $this->belongsTo(Notice::class);
    }

    public function publisher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'published_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
