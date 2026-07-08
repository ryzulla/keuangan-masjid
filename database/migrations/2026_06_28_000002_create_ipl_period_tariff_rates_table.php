<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Stores per-period amounts for EXTRA tariff types (billing_key = null).
        // Security and garbage amounts remain on ipl_periods directly.
        Schema::create('ipl_period_tariff_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ipl_period_id')
                  ->constrained('ipl_periods')
                  ->cascadeOnDelete();
            $table->foreignId('ipl_tariff_type_id')
                  ->constrained('ipl_tariff_types')
                  ->cascadeOnDelete();
            $table->decimal('amount', 15, 2)->default(0);
            $table->timestamps();

            $table->unique(['ipl_period_id', 'ipl_tariff_type_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ipl_period_tariff_rates');
    }
};
