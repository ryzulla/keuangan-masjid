<?php
namespace App\Observers;
use App\Models\Transaction;
use App\Models\Account;

class TransactionObserver
{
    public function created(Transaction $transaction): void
    {
        $account = $transaction->account;
        if ($transaction->type === 'debit') {
            $account->balance = (float)$account->balance + (float)$transaction->amount;
        } elseif ($transaction->type === 'credit') {
            $account->balance = (float)$account->balance - (float)$transaction->amount;
        }
        $account->save();
    }

    public function updated(Transaction $transaction): void
    {
        // Ambil nilai sebelum dan sesudah update
        $originalAmount = (float) $transaction->getOriginal('amount');
        $originalType = $transaction->getOriginal('type');
        $originalAccountId = $transaction->getOriginal('account_id');

        $currentAmount = (float) $transaction->amount;
        $currentType = $transaction->type;
        $currentAccountId = $transaction->account_id;

        // KASUS 1: ID Akun BERUBAH
        if ($originalAccountId != $currentAccountId) {
            // --- Rollback Akun Lama ---
            $originalAccount = Account::find($originalAccountId);
            if ($originalAccount) {
                $balance = (float) $originalAccount->balance;
                if ($originalType === 'debit') {
                    $originalAccount->balance = $balance - $originalAmount;
                } elseif ($originalType === 'credit') {
                    $originalAccount->balance = $balance + $originalAmount;
                }
                $originalAccount->save();
            }

            // --- Terapkan ke Akun Baru ---
            $currentAccount = Account::find($currentAccountId);
            if ($currentAccount) {
                $balance = (float) $currentAccount->balance;
                if ($currentType === 'debit') {
                    $currentAccount->balance = $balance + $currentAmount;
                } elseif ($currentType === 'credit') {
                    $currentAccount->balance = $balance - $currentAmount;
                }
                $currentAccount->save();
            }
        }
        // KASUS 2: ID Akun SAMA (hanya amount/type/desc/dll yang berubah)
        else {
            $account = Account::find($currentAccountId);
            if ($account) {
                $balance = (float) $account->balance;

                // 1. Rollback efek lama
                if ($originalType === 'debit') {
                    $balance -= $originalAmount;
                } elseif ($originalType === 'credit') {
                    $balance += $originalAmount;
                }

                // 2. Terapkan efek baru
                if ($currentType === 'debit') {
                    $balance += $currentAmount;
                } elseif ($currentType === 'credit') {
                    $balance -= $currentAmount;
                }

                // 3. Simpan saldo akhir
                $account->balance = $balance;
                $account->save();
            }
        }
    }


    public function deleted(Transaction $transaction): void
    {
        $account = $transaction->account;
        if ($transaction->type === 'debit') {
            $account->balance = (float)$account->balance - (float)$transaction->amount;
        } elseif ($transaction->type === 'credit') {
            $account->balance = (float)$account->balance + (float)$transaction->amount;
        }
        $account->save();
    }
}
