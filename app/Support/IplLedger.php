<?php

namespace App\Support;

use App\Models\Category;
use App\Models\IplTariffType;
use App\Models\Transaction;

/**
 * Mencatat pembayaran IPL ke buku besar (Transaction) per komponen, masing-masing
 * ke AKUN TUJUAN sesuai Pengaturan (via PaymentAccounts). Dipakai bersama oleh
 * konfirmasi portal (PaymentRequestAdmin) dan pencatatan manual admin (ManageIPL)
 * supaya konsisten.
 */
class IplLedger
{
    /**
     * @param  array<int|string,float>  $extras  [ipl_tariff_type_id => amount]
     * @return int|null  Akun "utama" (komponen pertama yang diposting) untuk disimpan di IplPayment.account_id.
     */
    public static function record(
        float $security,
        float $garbage,
        float $kasRt,
        array $extras,
        string $description,
        string $date,
        ?int $userId
    ): ?int {
        $primaryAccountId = null;
        $fallbackCategory = static::categoryId('Pendapatan Lain-lain Perumahan');

        $components = [
            ['key' => 'security', 'amount' => $security, 'label' => 'Security', 'category' => 'IPL Security'],
            ['key' => 'garbage',  'amount' => $garbage,  'label' => 'Sampah',   'category' => 'Iuran Sampah'],
            ['key' => 'kas_rt',   'amount' => $kasRt,    'label' => 'Kas RT',    'category' => 'Iuran Kas RT'],
        ];

        foreach ($components as $c) {
            if ($c['amount'] <= 0) {
                continue;
            }
            $accountId = PaymentAccounts::ipl($c['key']);
            if (! $accountId) {
                continue;
            }
            $primaryAccountId ??= $accountId;

            Transaction::create([
                'type'             => 'debit',
                'amount'           => $c['amount'],
                'account_id'       => $accountId,
                'category_id'      => static::categoryId($c['category']) ?? $fallbackCategory,
                'description'      => "{$c['label']}: {$description}",
                'transaction_date' => $date,
                'user_id'          => $userId,
            ]);
        }

        foreach ($extras as $typeId => $amount) {
            $amount = (float) $amount;
            if ($amount <= 0) {
                continue;
            }
            $type      = IplTariffType::find($typeId);
            $accountId = $type ? PaymentAccounts::forTariffType($type) : PaymentAccounts::firstOfOrg('perumahan');
            if (! $accountId) {
                continue;
            }
            $primaryAccountId ??= $accountId;

            Transaction::create([
                'type'             => 'debit',
                'amount'           => $amount,
                'account_id'       => $accountId,
                'category_id'      => $fallbackCategory,
                'description'      => ($type?->name ?? 'Biaya Tambahan') . ": {$description}",
                'transaction_date' => $date,
                'user_id'          => $userId,
            ]);
        }

        return $primaryAccountId ?? PaymentAccounts::ipl('security');
    }

    private static function categoryId(string $name): ?int
    {
        return Category::where('name', $name)
            ->where('organization_type', 'perumahan')
            ->where('type', 'income')
            ->value('id');
    }
}
