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
        Schema::table('ipl_tariff_types', function (Blueprint $table) {
            $table->foreignId('default_account_id')->nullable()->after('default_amount')
                ->constrained('accounts')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ipl_tariff_types', function (Blueprint $table) {
            $table->dropConstrainedForeignId('default_account_id');
        });
    }
};
