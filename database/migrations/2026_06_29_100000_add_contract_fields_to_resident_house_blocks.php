<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('resident_house_blocks', function (Blueprint $table) {
            $table->date('contract_start_date')->nullable()->after('resident_since');
            $table->date('contract_end_date')->nullable()->after('contract_start_date');
            $table->decimal('monthly_rent', 12, 2)->nullable()->after('contract_end_date');
            $table->timestamp('ended_at')->nullable()->after('notes');
            $table->index(['house_block_id', 'ended_at']);
        });
    }

    public function down(): void
    {
        Schema::table('resident_house_blocks', function (Blueprint $table) {
            $table->dropIndex(['house_block_id', 'ended_at']);
            $table->dropColumn(['contract_start_date', 'contract_end_date', 'monthly_rent', 'ended_at']);
        });
    }
};
