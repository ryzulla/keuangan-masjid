<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Role extends Model
{
    protected $fillable = ['key', 'label', 'color', 'group', 'is_system', 'is_active', 'sort'];

    protected $casts = ['is_system' => 'boolean', 'is_active' => 'boolean'];

    protected static function booted(): void
    {
        // Segarkan cache saat role berubah.
        static::saved(fn () => static::flushCache());
        static::deleted(fn () => static::flushCache());
    }

    public static function flushCache(): void
    {
        Cache::forget('roles_all');
    }

    /** Semua role (cached), terurut. */
    public static function allCached()
    {
        return Cache::rememberForever('roles_all', fn () => static::orderBy('sort')->orderBy('label')->get());
    }

    /** [key => label] */
    public static function labels(): array
    {
        return static::allCached()->pluck('label', 'key')->all();
    }

    /** [key => color] */
    public static function colors(): array
    {
        return static::allCached()->pluck('color', 'key')->all();
    }

    /** Daftar key valid untuk validasi. */
    public static function keys(): array
    {
        return static::allCached()->pluck('key')->all();
    }

    /** Key role yang berstatus aktif (super_admin selalu dianggap aktif). */
    public static function activeKeys(): array
    {
        return static::allCached()
            ->filter(fn ($r) => $r->is_active || $r->key === 'super_admin')
            ->pluck('key')->all();
    }

    public static function isActive(string $key): bool
    {
        return in_array($key, static::activeKeys(), true);
    }

    public static function labelFor(string $key): string
    {
        return static::labels()[$key] ?? ucfirst(str_replace('_', ' ', $key));
    }

    public static function colorFor(string $key): string
    {
        return static::colors()[$key] ?? '#586359';
    }
}
