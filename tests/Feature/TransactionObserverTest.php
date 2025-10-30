<?php

use App\Models\Account;
use App\Models\User;
use App\Models\Category;
use App\Models\Transaction;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->incomeCategory = Category::factory()->create(['type' => 'income']);
    $this->expenseCategory = Category::factory()->create(['type' => 'expense']);
    $this->kasUtama = Account::factory()->create(['balance' => 1000000]); // Saldo awal 1 Juta
    $this->bank = Account::factory()->create(['balance' => 5000000]); // Saldo awal 5 Juta
});

test('creating a debit transaction increases account balance', function () {
    Transaction::create([
        'account_id' => $this->kasUtama->id,
        'category_id' => $this->incomeCategory->id,
        'user_id' => $this->user->id,
        'description' => 'Test Infaq',
        'amount' => 500000,
        'type' => 'debit',
        'transaction_date' => now(),
    ]);

    // GANTI .toBe() menjadi .toEqual()
    expect($this->kasUtama->fresh()->balance)->toEqual(1500000.00);
});

test('creating a credit transaction decreases account balance', function () {
    Transaction::create([
        'account_id' => $this->kasUtama->id,
        'category_id' => $this->expenseCategory->id,
        'user_id' => $this->user->id,
        'description' => 'Test Listrik',
        'amount' => 200000,
        'type' => 'credit',
        'transaction_date' => now(),
    ]);

    // GANTI .toBe() menjadi .toEqual()
    expect($this->kasUtama->fresh()->balance)->toEqual(800000.00);
});

test('deleting a debit transaction decreases account balance', function () {
    $transaction = Transaction::factory()->create([
        'account_id' => $this->kasUtama->id,
        'category_id' => $this->incomeCategory->id, // Tambahkan kategori
        'user_id' => $this->user->id,             // Tambahkan user
        'amount' => 500000,
        'type' => 'debit',
        'transaction_date' => now(),             // Tambahkan tanggal
    ]);
    // GANTI .toBe() menjadi .toEqual()
    expect($this->kasUtama->fresh()->balance)->toEqual(1500000.00); // Saldo setelah create

    $transaction->delete();

    // GANTI .toBe() menjadi .toEqual()
    expect($this->kasUtama->fresh()->balance)->toEqual(1000000.00); // Saldo setelah delete
});

test('deleting a credit transaction increases account balance', function () {
    $transaction = Transaction::factory()->create([
        'account_id' => $this->kasUtama->id,
        'category_id' => $this->expenseCategory->id, // Tambahkan kategori
        'user_id' => $this->user->id,              // Tambahkan user
        'amount' => 300000,
        'type' => 'credit',
        'transaction_date' => now(),              // Tambahkan tanggal
    ]);
    // GANTI .toBe() menjadi .toEqual()
    expect($this->kasUtama->fresh()->balance)->toEqual(700000.00); // Saldo setelah create

    $transaction->delete();

    // GANTI .toBe() menjadi .toEqual()
    expect($this->kasUtama->fresh()->balance)->toEqual(1000000.00); // Saldo setelah delete
});


test('updating a transaction amount correctly adjusts balance', function () {
    $transaction = Transaction::factory()->create([
        'account_id' => $this->kasUtama->id,
        'category_id' => $this->incomeCategory->id,
        'user_id' => $this->user->id,
        'amount' => 500000,
        'type' => 'debit',
        'transaction_date' => now(),
    ]);
    // GANTI .toBe() menjadi .toEqual()
    expect($this->kasUtama->fresh()->balance)->toEqual(1500000.00); // Saldo setelah create

    $transaction->update(['amount' => 400000]);

    // GANTI .toBe() menjadi .toEqual()
    expect($this->kasUtama->fresh()->balance)->toEqual(1400000.00); // Saldo setelah update
});


test('updating a transaction account correctly moves balance', function () {
    $transaction = Transaction::factory()->create([
        'account_id' => $this->kasUtama->id,
        'category_id' => $this->incomeCategory->id,
        'user_id' => $this->user->id,
        'amount' => 500000,
        'type' => 'debit',
        'transaction_date' => now(),
    ]);
    // GANTI .toBe() menjadi .toEqual()
    expect($this->kasUtama->fresh()->balance)->toEqual(1500000.00);
    // GANTI .toBe() menjadi .toEqual()
    expect($this->bank->fresh()->balance)->toEqual(5000000.00);

    $transaction->update(['account_id' => $this->bank->id]);

    // GANTI .toBe() menjadi .toEqual()
    expect($this->kasUtama->fresh()->balance)->toEqual(1000000.00);
    // GANTI .toBe() menjadi .toEqual()
    expect($this->bank->fresh()->balance)->toEqual(5500000.00);
});
