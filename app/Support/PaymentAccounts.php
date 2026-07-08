<?php

namespace App\Support;

use App\Models\Account;
use App\Models\IplTariffType;
use App\Models\Setting;

/**
 * Sumber tunggal untuk menentukan AKUN TUJUAN pembayaran.
 *
 * - IPL (security / garbage / kas_rt & tarif tambahan): dari
 *   ipl_tariff_types.default_account_id (diatur di Pengaturan Tarif).
 * - Donasi: dari settings `donation_account_perumahan` / `donation_account_dkm`.
 *
 * Semua punya fallback ke akun pertama organisasi terkait agar posting ledger
 * tidak pernah gagal walau admin belum mengatur.
 */
class PaymentAccounts
{
    /** Akun tujuan komponen IPL berdasarkan billing_key: security|garbage|kas_rt. */
    public static function ipl(string $billingKey): ?int
    {
        $accountId = IplTariffType::where('billing_key', $billingKey)->value('default_account_id');
        return $accountId ?: static::firstOfOrg('perumahan');
    }

    /** Akun tujuan sebuah tarif tambahan (extra type). */
    public static function forTariffType(IplTariffType $type): ?int
    {
        return $type->default_account_id ?: static::firstOfOrg('perumahan');
    }

    /** Akun tujuan donasi berdasarkan organisasi program: perumahan|dkm. */
    public static function donation(string $org): ?int
    {
        $org = in_array($org, ['perumahan', 'dkm'], true) ? $org : 'dkm';
        return Setting::getInt("donation_account_{$org}") ?: static::firstOfOrg($org);
    }

    /** Akun pertama (fallback) dari sebuah organisasi. */
    public static function firstOfOrg(string $org): ?int
    {
        return Account::where('organization_type', $org)->orderBy('id')->value('id');
    }
}
