<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('ipl_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ipl_billing_id')->constrained('ipl_billings')->cascadeOnDelete();
            $table->date('payment_date');
            $table->decimal('amount_security', 15, 2)->default(0);
            $table->decimal('amount_garbage', 15, 2)->default(0);
            $table->enum('payment_method', ['cash', 'transfer', 'other'])->default('cash');
            $table->foreignId('account_id')->nullable()->constrained('accounts')->nullOnDelete();
            $table->string('reference_number')->nullable();
            $table->string('received_by')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('ipl_payments');
    }
};
