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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Contoh: "Kas Utama", "Bank Syariah Operasional"

            // Saldo saat ini.
            // 15 digit total, 2 digit di belakang koma (untuk rupiah)
            // Saldo ini akan di-update oleh Observer, bukan manual.
            $table->decimal('balance', 15, 2)->default(0.00);

            $table->text('description')->nullable(); // Keterangan tambahan (opsional)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
