<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'organization_type', 'balance', 'description'];

    protected $casts = ['balance' => 'decimal:2'];

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function iplPayments(): HasMany
    {
        return $this->hasMany(IplPayment::class);
    }

    public function scopeByOrg($query, string $type)
    {
        return $query->where('organization_type', $type);
    }
}
