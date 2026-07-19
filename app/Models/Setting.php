<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function get(string $key, $default = null)
    {
        return static::query()->where('key', $key)->value('value') ?? $default;
    }

    public static function getInt(string $key): ?int
    {
        $v = static::get($key);
        return ($v === null || $v === '') ? null : (int) $v;
    }

    public static function set(string $key, $value): void
    {
        static::query()->updateOrCreate(
            ['key' => $key],
            ['value' => $value === null ? null : (string) $value]
        );
    }

    /**
     * Nama perumahan/aplikasi yang dapat diatur superadmin.
     * Jatuh ke config('app.name') bila belum pernah diisi.
     */
    public static function appName(): string
    {
        $name = static::get('app_name');
        return ($name === null || $name === '') ? config('app.name', 'Sistem Perumahan') : $name;
    }

    /**
     * Subtitle/tagline aplikasi (dipakai di brand navbar & footer).
     */
    public static function appSubtitle(): string
    {
        $sub = static::get('app_subtitle');
        return ($sub === null || $sub === '') ? 'Sistem Manajemen Perumahan & DKM' : $sub;
    }

    /**
     * Apakah sebuah modul aktif. Default AKTIF bila belum pernah diatur.
     * $module: 'perumahan' | 'dkm'
     */
    public static function moduleEnabled(string $module): bool
    {
        return static::get("module_{$module}_enabled", '1') !== '0';
    }

    /**
     * Daftar organization_type yang modulnya aktif — dipakai memfilter
     * program, keuangan, dll baik di admin, portal penghuni, maupun publik.
     */
    public static function enabledOrgs(): array
    {
        $orgs = [];
        if (static::moduleEnabled('perumahan')) $orgs[] = 'perumahan';
        if (static::moduleEnabled('dkm')) $orgs[] = 'dkm';
        return $orgs;
    }
}
