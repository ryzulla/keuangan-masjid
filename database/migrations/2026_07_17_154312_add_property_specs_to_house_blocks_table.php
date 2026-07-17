<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('house_blocks', function (Blueprint $table) {
            $table->decimal('land_area', 8, 2)->nullable()->after('rental_duration')->comment('Luas Tanah m²');
            $table->decimal('building_area', 8, 2)->nullable()->after('land_area')->comment('Luas Bangunan m²');
            $table->string('water_source', 20)->nullable()->after('building_area')->comment('pdam/tanah/both');
            $table->integer('electricity')->nullable()->after('water_source')->comment('Watt');
            $table->tinyInteger('bedrooms')->nullable()->after('electricity')->comment('Kamar Tidur');
            $table->tinyInteger('bathrooms')->nullable()->after('bedrooms')->comment('Kamar Mandi');
        });
    }

    public function down(): void
    {
        Schema::table('house_blocks', function (Blueprint $table) {
            $table->dropColumn(['land_area', 'building_area', 'water_source', 'electricity', 'bedrooms', 'bathrooms']);
        });
    }
};
