<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('citizen_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resident_id')->constrained()->cascadeOnDelete();
            $table->enum('category', ['sakit', 'meninggal', 'lainnya']);
            $table->enum('report_for', ['diri_sendiri', 'keluarga', 'warga_lain']);
            $table->string('person_name')->nullable();
            $table->text('description');
            $table->enum('status', ['pending', 'published', 'dismissed'])->default('pending');
            $table->foreignId('notice_id')->nullable()->constrained('notices')->nullOnDelete();
            $table->foreignId('published_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('citizen_reports');
    }
};
