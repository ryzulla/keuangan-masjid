<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notice_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('notice_id')->constrained()->cascadeOnDelete();
            $table->foreignId('resident_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['notice_id', 'resident_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notice_likes');
    }
};
