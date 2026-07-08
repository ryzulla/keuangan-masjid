<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('resident_payment_requests', function (Blueprint $table) {
            $table->enum('donation_form', ['uang', 'barang'])->default('uang')->after('campaign_id');
            $table->string('donation_type')->nullable()->after('donation_form');
        });
    }

    public function down(): void
    {
        Schema::table('resident_payment_requests', function (Blueprint $table) {
            $table->dropColumn(['donation_form', 'donation_type']);
        });
    }
};
