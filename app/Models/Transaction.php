<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Transaction extends Model {
    use HasFactory;
    protected $guarded = []; // Izinkan semua mass assignment
    protected $casts = [ 'amount' => 'decimal:2', 'transaction_date' => 'date' ];

    public function account(): BelongsTo { return $this->belongsTo(Account::class); }
    public function category(): BelongsTo { return $this->belongsTo(Category::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function donation(): HasOne { return $this->hasOne(Donation::class); }
    // --- TAMBAHKAN RELASI INI ---
    public function campaign(): BelongsTo { return $this->belongsTo(Campaign::class); }
    // -----------------------------
}
