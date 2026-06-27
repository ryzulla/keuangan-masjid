<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('house_blocks', function (Blueprint $table) {
            $table->id();
            $table->string('block_letter', 2);
            $table->tinyInteger('unit_number')->unsigned();
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['block_letter', 'unit_number']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('house_blocks');
    }
};
