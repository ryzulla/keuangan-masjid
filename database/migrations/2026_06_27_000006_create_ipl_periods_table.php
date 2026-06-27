<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('ipl_periods', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('year')->unsigned();
            $table->tinyInteger('month')->unsigned();
            $table->decimal('ipl_security_amount', 15, 2)->default(0);
            $table->decimal('ipl_garbage_amount', 15, 2)->default(0);
            $table->text('notes')->nullable();
            $table->boolean('is_closed')->default(false);
            $table->timestamps();
            $table->unique(['year', 'month']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('ipl_periods');
    }
};
