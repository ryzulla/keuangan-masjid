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
        Schema::table('resident_house_blocks', function (Blueprint $table) {
            // Penanda penanggung IPL untuk unit ini (mis. penyewa yang bayar IPL,
            // bukan pemilik). Default false → fallback ke pemilik aktif saat generate.
            $table->boolean('is_ipl_payer')->default(false)->after('is_primary_residence');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resident_house_blocks', function (Blueprint $table) {
            $table->dropColumn('is_ipl_payer');
        });
    }
};
