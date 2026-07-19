<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Penghuni yang dipromosikan menjadi admin (null = akun admin biasa).
            $table->foreignId('resident_id')->nullable()->after('id')
                ->constrained('residents')->nullOnDelete();
            // Nonaktifkan akses tanpa menghapus akun (dipakai untuk "cabut" akses admin penghuni).
            $table->boolean('is_active')->default(true)->after('role');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('resident_id');
            $table->dropColumn('is_active');
        });
    }
};
