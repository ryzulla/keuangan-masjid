<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ipl_periods', function (Blueprint $table) {
            $table->decimal('ipl_kas_rt_amount', 15, 2)->default(0)->after('ipl_garbage_amount');
        });

        Schema::table('ipl_billings', function (Blueprint $table) {
            $table->decimal('ipl_kas_rt_amount', 15, 2)->default(0)->after('ipl_garbage_amount');
            $table->decimal('paid_kas_rt', 15, 2)->default(0)->after('paid_garbage');
        });

        Schema::table('ipl_payments', function (Blueprint $table) {
            $table->decimal('amount_kas_rt', 15, 2)->default(0)->after('amount_garbage');
        });
    }

    public function down(): void
    {
        Schema::table('ipl_payments', function (Blueprint $table) {
            $table->dropColumn('amount_kas_rt');
        });

        Schema::table('ipl_billings', function (Blueprint $table) {
            $table->dropColumn(['ipl_kas_rt_amount', 'paid_kas_rt']);
        });

        Schema::table('ipl_periods', function (Blueprint $table) {
            $table->dropColumn('ipl_kas_rt_amount');
        });
    }
};
