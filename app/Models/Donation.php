<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Donation extends Model {
    use HasFactory;
    protected $guarded = []; // Izinkan semua mass assignment

    public function transaction(): BelongsTo { return $this->belongsTo(Transaction::class); }
    public function donor(): BelongsTo { return $this->belongsTo(Donor::class); }
    public function campaign(): BelongsTo { return $this->belongsTo(Campaign::class); }
}
