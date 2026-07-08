<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ipl_payments', function (Blueprint $table) {
            // JSON: { "tariff_type_id": amount_paid, ... }
            // Stored here so payment history is preserved and updateStatus() can recalculate
            $table->json('extra_charges_paid')->nullable()->after('notes');
        });
    }

    public function down(): void
    {
        Schema::table('ipl_payments', function (Blueprint $table) {
            $table->dropColumn('extra_charges_paid');
        });
    }
};
