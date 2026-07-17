<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('house_blocks', function (Blueprint $table) {
            $table->boolean('is_for_rent')->default(false);
            $table->decimal('rental_price', 12, 2)->nullable();
            $table->text('rental_description')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('house_blocks', function (Blueprint $table) {
            $table->dropColumn(['is_for_rent', 'rental_price', 'rental_description']);
        });
    }
};
