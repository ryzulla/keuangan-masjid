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
        Schema::table('resident_payment_requests', function (Blueprint $table) {
            // Rincian pembayaran IPL per komponen (bayar sebagian: keamanan/sampah/kas RT).
            $table->decimal('amount_security', 12, 2)->default(0)->after('amount');
            $table->decimal('amount_garbage', 12, 2)->default(0)->after('amount_security');
            $table->decimal('amount_kas_rt', 12, 2)->default(0)->after('amount_garbage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resident_payment_requests', function (Blueprint $table) {
            $table->dropColumn(['amount_security', 'amount_garbage', 'amount_kas_rt']);
        });
    }
};
