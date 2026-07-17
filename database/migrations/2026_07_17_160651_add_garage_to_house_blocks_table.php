<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('house_blocks', function (Blueprint $table) {
            $table->tinyInteger('garage')->nullable()->after('bathrooms')->comment('Kapasitas garasi (mobil)');
        });
    }

    public function down(): void
    {
        Schema::table('house_blocks', function (Blueprint $table) {
            $table->dropColumn('garage');
        });
    }
};
