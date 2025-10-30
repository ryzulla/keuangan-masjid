<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            // Relasi ke Akun: Uang ini dari/ke Akun mana?
            $table->foreignId('account_id')->constrained('accounts');

            // Relasi ke Kategori: Untuk keperluan apa transaksi ini?
            $table->foreignId('category_id')->constrained('categories');

            // Relasi ke User: Siapa yang mencatat transaksi ini?
            $table->foreignId('user_id')->constrained('users');

            $table->string('description'); // Keterangan/Uraian transaksi

            // Jumlah uang. Selalu positif.
            $table->decimal('amount', 15, 2);

            // Tipe transaksi:
            // 'debit' = Uang Masuk (menambah saldo akun)
            // 'credit' = Uang Keluar (mengurangi saldo akun)
            $table->enum('type', ['debit', 'credit']);

            $table->date('transaction_date'); // Tanggal kejadian transaksi

            $table->timestamps(); // Kapan transaksi ini dicatat di sistem
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
