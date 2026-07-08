<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('family_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resident_id')->constrained('residents')->cascadeOnDelete();
            $table->string('name');
            $table->enum('relationship', ['istri', 'suami', 'anak', 'orang_tua', 'mertua', 'saudara', 'lainnya'])->default('lainnya');
            $table->enum('gender', ['laki-laki', 'perempuan'])->default('laki-laki');
            $table->text('nik')->nullable();
            $table->date('birth_date')->nullable();
            $table->text('notes')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('family_members');
    }
};
