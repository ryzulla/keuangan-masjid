<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('donations', function (Blueprint $table) {
            $table->enum('donor_type', ['warga', 'luaran'])->default('luaran')->after('donor_name');
            $table->enum('donation_form', ['uang', 'barang'])->default('uang')->after('donor_type');
            $table->string('item_description', 255)->nullable()->after('donation_form');
            $table->string('item_quantity', 100)->nullable()->after('item_description');
            $table->string('item_photo_path', 500)->nullable()->after('item_quantity');
            $table->unsignedBigInteger('resident_id')->nullable()->after('donor_id');
            $table->foreign('resident_id')->references('id')->on('residents')->nullOnDelete();
        });
    }
    public function down(): void {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropForeign(['resident_id']);
            $table->dropColumn(['donor_type', 'donation_form', 'item_description', 'item_quantity', 'item_photo_path', 'resident_id']);
        });
    }
};
