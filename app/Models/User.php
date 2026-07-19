<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany; // <-- TAMBAHKAN INI
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // <-- TAMBAHKAN 'role' DI SINI
        'resident_id',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    /**
     * Penghuni asal, bila akun ini dibuat dari promosi penghuni menjadi admin.
     */
    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }

    /**
     * TAMBAHKAN FUNGSI RELASI DI BAWAH INI
     * * RELASI: Satu User 'hasMany' (memiliki banyak) Transaksi.
     */
    /**
     * Get all transactions for the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
