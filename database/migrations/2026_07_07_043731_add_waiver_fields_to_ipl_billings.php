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
        Schema::table('ipl_billings', function (Blueprint $table) {
            // Pemutihan/pembebasan tunggakan per komponen (bukan pemasukan kas).
            $table->decimal('waived_security', 12, 2)->default(0)->after('paid_kas_rt');
            $table->decimal('waived_garbage', 12, 2)->default(0)->after('waived_security');
            $table->decimal('waived_kas_rt', 12, 2)->default(0)->after('waived_garbage');
            $table->string('waiver_reason')->nullable()->after('waived_kas_rt');
            $table->unsignedBigInteger('waived_by')->nullable()->after('waiver_reason');
            $table->timestamp('waived_at')->nullable()->after('waived_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ipl_billings', function (Blueprint $table) {
            $table->dropColumn([
                'waived_security', 'waived_garbage', 'waived_kas_rt',
                'waiver_reason', 'waived_by', 'waived_at',
            ]);
        });
    }
};
