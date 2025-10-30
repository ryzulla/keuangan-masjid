<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    /**
     * Kolom yang boleh diisi
     */
    protected $fillable = ['name', 'type'];

    /**
     * RELASI: Satu Kategori 'hasMany' (memiliki banyak) Transaksi.
     * Nama fungsi (transactions) HARUS jamak (plural).
     */
    public function transactions()
    {
        // Kita bisa panggil $kategori->transactions
        return $this->hasMany(Transaction::class);
    }
}
