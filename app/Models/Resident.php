<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Resident extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'photo', 'nik', 'phone', 'whatsapp', 'email', 'notes', 'is_active',
        'password', 'notification_preferences',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'nik'                      => 'encrypted',
        'is_active'                => 'boolean',
        'notification_preferences' => 'array',
    ];

    /**
     * Daftar preferensi notifikasi yang tersedia beserta labelnya.
     * Semua default aktif (true) bila belum pernah diatur penghuni.
     */
    public const NOTIFICATION_TYPES = [
        'ipl_reminder'   => 'Pengingat tagihan IPL',
        'payment_status' => 'Status konfirmasi pembayaran',
        'program_update' => 'Info program & kampanye',
    ];

    /**
     * Apakah penghuni ingin menerima jenis notifikasi tertentu.
     * Default true jika preferensi belum pernah diatur.
     */
    public function wantsNotification(string $key): bool
    {
        return (bool) ($this->notification_preferences[$key] ?? true);
    }

    public function houseBlocks(): BelongsToMany
    {
        return $this->belongsToMany(HouseBlock::class, 'resident_house_blocks')
            ->withPivot('ownership_type', 'occupancy_status', 'resident_since',
                        'contract_start_date', 'contract_end_date', 'monthly_rent',
                        'is_primary_residence', 'notes', 'ended_at')
            ->wherePivotNull('ended_at')
            ->withTimestamps();
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(ResidentHouseBlock::class);
    }

    public function currentAssignments(): HasMany
    {
        return $this->hasMany(ResidentHouseBlock::class)->whereNull('ended_at');
    }

    public function primaryHouseBlock(): ?HouseBlock
    {
        return $this->houseBlocks()->wherePivot('is_primary_residence', true)->first();
    }

    public function familyMembers(): HasMany
    {
        return $this->hasMany(FamilyMember::class)->orderBy('sort_order')->orderBy('id');
    }

    public function iplBillings(): HasMany
    {
        return $this->hasMany(IplBilling::class, 'responsible_resident_id');
    }

    public function paymentRequests(): HasMany
    {
        return $this->hasMany(ResidentPaymentRequest::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function isPemilik(): bool
    {
        return $this->currentAssignments()->where('ownership_type', 'pemilik')->exists();
    }
}
