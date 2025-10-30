<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Model
{
    use HasFactory;

    /**
     * Kolom yang boleh diisi
     */
    protected $fillable = ['name', 'balance', 'description'];

    /**
     * Konversi tipe data untuk 'balance'
     */
    protected $casts = [
        'balance' => 'decimal:2',
    ];

    /**
     * RELASI: Satu Akun 'hasMany' (memiliki banyak) Transaksi.
     * Nama fungsi (transactions) HARUS jamak (plural).
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
