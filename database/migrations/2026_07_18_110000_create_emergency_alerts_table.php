<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('emergency_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resident_id')->constrained()->cascadeOnDelete();
            $table->string('block_code');
            $table->string('message')->default('Darurat!');
            $table->boolean('is_active')->default(true);
            $table->foreignId('stopped_by')->nullable()->constrained('residents')->nullOnDelete();
            $table->timestamp('stopped_at')->nullable();
            $table->timestamps();

            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('emergency_alerts');
    }
};
