<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('house_block_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('house_block_id')->constrained('house_blocks')->cascadeOnDelete();
            $table->string('photo_path');
            $table->string('caption')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_primary')->default(false);
            $table->timestamps();

            $table->index('house_block_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('house_block_photos');
    }
};
