<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('residents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('house_block_id')->nullable()->constrained('house_blocks')->nullOnDelete();
            $table->string('name');
            $table->text('nik')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('whatsapp', 20)->nullable();
            $table->string('email')->nullable();
            $table->enum('ownership_status', ['pemilik', 'kontrak', 'kos'])->default('pemilik');
            $table->enum('occupancy_status', ['dihuni', 'kosong'])->default('dihuni');
            $table->date('resident_since')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('residents');
    }
};
