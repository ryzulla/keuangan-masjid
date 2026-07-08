<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tracks billed and paid amounts for EXTRA tariff types per billing.
        Schema::create('ipl_billing_charge_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ipl_billing_id')
                  ->constrained('ipl_billings')
                  ->cascadeOnDelete();
            $table->foreignId('ipl_tariff_type_id')
                  ->constrained('ipl_tariff_types')
                  ->cascadeOnDelete();
            $table->decimal('billed_amount', 15, 2)->default(0);
            $table->decimal('paid_amount', 15, 2)->default(0);
            $table->timestamps();

            $table->unique(['ipl_billing_id', 'ipl_tariff_type_id'], 'ipl_bci_billing_type_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ipl_billing_charge_items');
    }
};
