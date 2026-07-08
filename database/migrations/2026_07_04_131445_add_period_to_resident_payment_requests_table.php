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
            $table->unsignedSmallInteger('period_year')->nullable()->after('ipl_billing_id');
            $table->unsignedTinyInteger('period_month')->nullable()->after('period_year');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resident_payment_requests', function (Blueprint $table) {
            $table->dropColumn(['period_year', 'period_month']);
        });
    }
};
