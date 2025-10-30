<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('donations', function (Blueprint $table) {
            // Tambahkan kolom nama donatur setelah donor_id
            $table->string('donor_name')->nullable()->after('donor_id');
        });
    }
    public function down(): void {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropColumn('donor_name');
        });
    }
};
