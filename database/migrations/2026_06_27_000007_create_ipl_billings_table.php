<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('ipl_billings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ipl_period_id')->constrained('ipl_periods')->cascadeOnDelete();
            $table->foreignId('resident_id')->constrained('residents')->cascadeOnDelete();
            $table->foreignId('house_block_id')->nullable()->constrained('house_blocks')->nullOnDelete();
            $table->decimal('ipl_security_amount', 15, 2)->default(0);
            $table->decimal('ipl_garbage_amount', 15, 2)->default(0);
            $table->decimal('paid_security', 15, 2)->default(0);
            $table->decimal('paid_garbage', 15, 2)->default(0);
            $table->enum('status', ['unpaid', 'partial', 'paid'])->default('unpaid');
            $table->date('due_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['ipl_period_id', 'resident_id']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('ipl_billings');
    }
};
