<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->id();

            // 1. Uang-nya
            $table->foreignId('transaction_id')->constrained('transactions')->onDelete('cascade');

            // 2. Orangnya (bisa null jika 'Hamba Allah')
            $table->foreignId('donor_id')->nullable()->constrained('donors');

            // 3. Programnya (bisa null jika donasi umum, bukan program spesifik)
            $table->foreignId('campaign_id')->nullable()->constrained('campaigns');

            // Tipe donasi (Zakat, Infaq, Wakaf, dll)
            $table->string('type')->default('infaq');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
