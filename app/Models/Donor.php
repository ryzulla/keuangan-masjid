<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Donor extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'phone', 'email', 'address'];

    /**
     * RELASI: Satu Donatur 'hasMany' (memiliki banyak) Donasi.
     */
    public function donations()
    {
        return $this->hasMany(Donation::class);
    }
}
