<?php

namespace App\Models; // Adjust namespace if your models are elsewhere

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Campaign extends Model
{
    use HasFactory;

    // Allow mass assignment for these fields
    protected $fillable = [
        'name',
        'description',
        'target_amount',
        'start_date',
        'end_date',
        'status',
    ];

    /**
     * Cast attributes to native types.
     */
    protected $casts = [
        'target_amount' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Relationship: A Campaign has many Donations.
     */
    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class);
    }

    /**
     * Relationship: A Campaign has many Transactions through Donations.
     * This allows easily summing up the collected amount.
     */
    public function transactions(): HasManyThrough
    {
        // campaign -> donations -> transactions
        return $this->hasManyThrough(
            Transaction::class,    // Final desired model
            Donation::class,       // Intermediate model
            'campaign_id',        // Foreign key on donations table...
            'id',                  // Foreign key on transactions table...
            'id',                  // Local key on campaigns table...
            'transaction_id'      // Local key on donations table...
        );
    }
}
