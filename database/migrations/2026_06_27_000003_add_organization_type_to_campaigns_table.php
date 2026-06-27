<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->enum('organization_type', ['perumahan', 'dkm'])->default('dkm')->after('name');
            $table->foreignId('source_account_id')->nullable()->constrained('accounts')->nullOnDelete()->after('organization_type');
        });
    }
    public function down(): void {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropForeign(['source_account_id']);
            $table->dropColumn(['organization_type', 'source_account_id']);
        });
    }
};
