<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\IplPeriod;
use App\Models\IplBilling;
use App\Models\IplPayment;
use App\Models\HouseBlock;
use App\Models\Account;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class IplPeriodSeeder extends Seeder
{
    public function run(): void
    {
        // Delete existing period seeded before
        IplBilling::query()->delete();
        IplPeriod::query()->delete();

        $securityAmount = 75000;
        $garbageAmount = 25000;
        $perumahanAccount = Account::where('organization_type', 'perumahan')->first();
        $adminUser = User::first();

        $periods = [];
        for ($month = 1; $month <= 6; $month++) {
            $periods[] = IplPeriod::create([
                'year' => 2026,
                'month' => $month,
                'ipl_security_amount' => $securityAmount,
                'ipl_garbage_amount' => $garbageAmount,
                'notes' => 'Tarif IPL periode ' . Carbon::create(2026, $month, 1)->locale('id')->isoFormat('MMMM YYYY'),
                'is_closed' => $month < 6,
            ]);
        }

        // Get all active blocks that have residents
        $assignedBlocks = DB::table('resident_house_blocks')
            ->select('house_block_id')
            ->distinct()
            ->get()
            ->pluck('house_block_id');

        $assignedHouseBlocks = HouseBlock::whereIn('id', $assignedBlocks)->get();

        foreach ($periods as $periodIndex => $period) {
            $month = $period->month;
            $paymentRate = match (true) {
                $month <= 3 => 0.82,  // Jan-Mar: 82% paid
                $month === 4 => 0.75, // Apr: 75%
                $month === 5 => 0.65, // May: 65%
                default => 0.40,      // Jun: 40% (current)
            };

            foreach ($assignedHouseBlocks as $block) {
                // Get owner from pivot
                $owner = DB::table('resident_house_blocks')
                    ->where('house_block_id', $block->id)
                    ->where('ownership_type', 'pemilik')
                    ->orderByDesc('is_primary_residence')
                    ->first();

                $billing = IplBilling::create([
                    'ipl_period_id' => $period->id,
                    'house_block_id' => $block->id,
                    'responsible_resident_id' => $owner?->resident_id,
                    'ipl_security_amount' => $securityAmount,
                    'ipl_garbage_amount' => $garbageAmount,
                    'paid_security' => 0,
                    'paid_garbage' => 0,
                    'status' => 'unpaid',
                    'due_date' => Carbon::create(2026, $month, 10)->format('Y-m-d'),
                ]);

                // Simulate payment based on rate
                if (mt_rand(1, 100) <= ($paymentRate * 100)) {
                    $paymentDay = mt_rand(1, 9);
                    IplPayment::create([
                        'ipl_billing_id' => $billing->id,
                        'payment_date' => Carbon::create(2026, $month, $paymentDay)->format('Y-m-d'),
                        'amount_security' => $securityAmount,
                        'amount_garbage' => $garbageAmount,
                        'payment_method' => mt_rand(0, 1) ? 'cash' : 'transfer',
                        'account_id' => $perumahanAccount?->id,
                        'received_by' => 'Bendahara RT',
                        'user_id' => $adminUser?->id ?? 1,
                    ]);
                    $billing->update([
                        'paid_security' => $securityAmount,
                        'paid_garbage' => $garbageAmount,
                        'status' => 'paid',
                    ]);
                } elseif ($month <= 4 && mt_rand(0, 1)) {
                    // Partial payment for some
                    IplPayment::create([
                        'ipl_billing_id' => $billing->id,
                        'payment_date' => Carbon::create(2026, $month, mt_rand(1, 20))->format('Y-m-d'),
                        'amount_security' => $securityAmount,
                        'amount_garbage' => 0,
                        'payment_method' => 'cash',
                        'account_id' => $perumahanAccount?->id,
                        'received_by' => 'Bendahara RT',
                        'user_id' => $adminUser?->id ?? 1,
                    ]);
                    $billing->update([
                        'paid_security' => $securityAmount,
                        'paid_garbage' => 0,
                        'status' => 'partial',
                    ]);
                }
            }
        }
    }
}
