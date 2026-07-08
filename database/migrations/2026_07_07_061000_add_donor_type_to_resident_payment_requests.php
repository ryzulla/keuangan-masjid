<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('resident_payment_requests', function (Blueprint $table) {
            $table->string('donor_type', 50)->default('warga')->after('donor_name');
        });
    }

    public function down(): void
    {
        Schema::table('resident_payment_requests', function (Blueprint $table) {
            $table->dropColumn('donor_type');
        });
    }
};
