<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kas_rt_programs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('type', ['proyek', 'acara', 'kegiatan', 'lain_lain'])->default('kegiatan');
            $table->string('short_description')->nullable();
            $table->longText('content')->nullable();
            $table->decimal('budget_amount', 15, 2)->default(0);
            $table->decimal('actual_amount', 15, 2)->default(0);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->enum('status', ['perencanaan', 'berjalan', 'selesai', 'dibatalkan'])->default('perencanaan');
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kas_rt_programs');
    }
};
