<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Notice extends Model
{
    protected $fillable = [
        'title', 'content', 'priority', 'is_published',
        'published_at', 'expires_at', 'created_by',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'expires_at'   => 'datetime',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function likers(): BelongsToMany
    {
        return $this->belongsToMany(Resident::class, 'notice_likes')->withTimestamps();
    }

    public function scopeActive($query)
    {
        return $query->where('is_published', true)
            ->where(fn($q) => $q->whereNull('published_at')->orWhere('published_at', '<=', now()))
            ->where(fn($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>', now()));
    }
}
