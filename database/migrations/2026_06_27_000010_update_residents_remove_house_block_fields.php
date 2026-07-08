<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        DB::statement('ALTER TABLE residents DROP FOREIGN KEY residents_house_block_id_foreign');
        DB::statement('ALTER TABLE residents DROP COLUMN house_block_id');
        DB::statement('ALTER TABLE residents DROP COLUMN ownership_status');
        DB::statement('ALTER TABLE residents DROP COLUMN occupancy_status');
        DB::statement('ALTER TABLE residents DROP COLUMN resident_since');
    }
    public function down(): void {
        Schema::table('residents', function ($table) {
            $table->foreignId('house_block_id')->nullable()->constrained('house_blocks')->nullOnDelete();
            $table->enum('ownership_status', ['pemilik', 'kontrak', 'kos'])->default('pemilik');
            $table->enum('occupancy_status', ['dihuni', 'kosong'])->default('dihuni');
            $table->date('resident_since')->nullable();
        });
    }
};
