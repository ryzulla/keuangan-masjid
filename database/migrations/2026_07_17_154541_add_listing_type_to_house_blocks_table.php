<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('house_blocks', function (Blueprint $table) {
            $table->string('listing_type', 10)->nullable()->after('is_for_rent')->comment('sewa/jual');
        });
    }

    public function down(): void
    {
        Schema::table('house_blocks', function (Blueprint $table) {
            $table->dropColumn('listing_type');
        });
    }
};
