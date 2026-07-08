<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('resident_house_blocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resident_id')->constrained('residents')->cascadeOnDelete();
            $table->foreignId('house_block_id')->constrained('house_blocks')->cascadeOnDelete();
            $table->enum('ownership_type', ['pemilik', 'kontrak', 'kos'])->default('pemilik');
            $table->enum('occupancy_status', ['dihuni', 'kosong'])->default('dihuni');
            $table->date('resident_since')->nullable();
            $table->boolean('is_primary_residence')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['resident_id', 'house_block_id']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('resident_house_blocks');
    }
};
