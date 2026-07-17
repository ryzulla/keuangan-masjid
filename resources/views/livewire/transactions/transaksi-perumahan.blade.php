<div class="min-h-screen py-6 px-4 sm:px-6 lg:px-8" style="background-color:#f7f7f7;">
<div class="max-w-7xl mx-auto space-y-6">

    {{-- Flash Messages --}}
    @if(session('success'))
    <div class="rounded-xl px-4 py-3 text-sm" style="background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.3);color:#12805c;">
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="rounded-xl px-4 py-3 text-sm" style="background:rgba(192,69,59,0.1);border:1px solid rgba(192,69,59,0.3);color:#c0453b;">
        {{ session('error') }}
    </div>
    @endif

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold" style="color:#161e2d;font-family:'Manrope',serif;">Transaksi Perumahan</h1>
            <p class="text-sm mt-1" style="color:#a3abb0;">Pembayaran IPL, Keamanan, dan penggunaan Kas RT</p>
        </div>
        <button wire:click="create"
            class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all"
            style="background:linear-gradient(135deg,#1563df,#1563df);color:#ffffff;">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Transaksi
        </button>
    </div>

    {{-- Dashboard Cards: Account Balances --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-{{ count($balances) > 0 ? count($balances) : 1 }} gap-4">
        @foreach($balances as $accId => $bal)
        <div class="rounded-xl p-4" style="background:#ffffff;border:1px solid #f7f7f7;">
            <p class="text-xs font-medium mb-1" style="color:#5c6368;">{{ $bal['name'] }}</p>
            <p class="text-xl font-bold {{ $bal['saldo'] >= 0 ? '' : '' }}" style="color:{{ $bal['saldo'] >= 0 ? '#1563df' : '#c0453b' }};">
                Rp {{ number_format(abs($bal['saldo']), 0, ',', '.') }}
            </p>
            <p class="text-xs mt-1" style="color:#a3abb0;">Saldo {{ $bal['saldo'] >= 0 ? 'tersedia' : '(minus)' }}</p>
        </div>
        @endforeach
    </div>

    {{-- IPL Collection Summary --}}
    @if($iplSummary)
    <div class="rounded-xl p-4" style="background:#ffffff;border:1px solid rgba(21,99,223,0.2);">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
            <div>
                <h3 class="text-sm font-semibold" style="color:#161e2d;">Rekapitulasi IPL</h3>
                <p class="text-xs" style="color:#a3abb0;">Periode: {{ $iplSummary['period'] }}</p>
            </div>
            <select wire:model.live="filterPeriodId"
                class="rounded-lg px-3 py-1.5 text-sm focus:outline-none"
                style="background:#ffffff;border:1px solid #e4e4e4;box-shadow:0 1px 2px rgba(21,99,223,0.04),0 8px 20px -8px rgba(21,99,223,0.06);color:#161e2d;min-width:160px;">
                @foreach($periods as $p)
                <option value="{{ $p->id }}">{{ $p->period_label }}</option>
                @endforeach
            </select>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            <div class="text-center p-3 rounded-lg" style="background:#ffffff;border:1px solid #ffffff;">
                <p class="text-xs mb-1" style="color:#5c6368;">Keamanan</p>
                <p class="text-sm font-bold" style="color:#161e2d;">Rp {{ number_format($iplSummary['security'], 0, ',', '.') }}</p>
            </div>
            <div class="text-center p-3 rounded-lg" style="background:#ffffff;border:1px solid #ffffff;">
                <p class="text-xs mb-1" style="color:#5c6368;">Kebersihan</p>
                <p class="text-sm font-bold" style="color:#161e2d;">Rp {{ number_format($iplSummary['garbage'], 0, ',', '.') }}</p>
            </div>
            <div class="text-center p-3 rounded-lg" style="background:#ffffff;border:1px solid #ffffff;">
                <p class="text-xs mb-1" style="color:#5c6368;">Kas RT</p>
                <p class="text-sm font-bold" style="color:#161e2d;">Rp {{ number_format($iplSummary['kas_rt'], 0, ',', '.') }}</p>
            </div>
            <div class="text-center p-3 rounded-lg" style="background:#f7f7f7;border:1px solid rgba(21,99,223,0.3);">
                <p class="text-xs mb-1" style="color:#161e2d;">Total IPL</p>
                <p class="text-sm font-bold" style="color:#161e2d;">Rp {{ number_format($iplSummary['total'], 0, ',', '.') }}</p>
            </div>
        </div>
    </div>
    @endif

    {{-- Filter Bar --}}
    <div class="rounded-xl p-4" style="background:#ffffff;border:1px solid #f7f7f7;">
        <div class="flex flex-wrap gap-3">
            <select wire:model.live="filterAccount"
                class="rounded-lg px-3 py-2 text-sm focus:outline-none"
                style="background:#ffffff;border:1px solid #e4e4e4;box-shadow:0 1px 2px rgba(21,99,223,0.04),0 8px 20px -8px rgba(21,99,223,0.06);color:#161e2d;min-width:160px;">
                <option value="">Semua Akun</option>
                @foreach($perumahanAccounts as $acc)
                <option value="{{ $acc->id }}">{{ $acc->name }}</option>
                @endforeach
            </select>
            <select wire:model.live="filterType"
                class="rounded-lg px-3 py-2 text-sm focus:outline-none"
                style="background:#ffffff;border:1px solid #e4e4e4;box-shadow:0 1px 2px rgba(21,99,223,0.04),0 8px 20px -8px rgba(21,99,223,0.06);color:#161e2d;min-width:130px;">
                <option value="">Semua Jenis</option>
                <option value="debit">Pemasukan</option>
                <option value="credit">Pengeluaran</option>
            </select>
            <div class="flex items-center gap-2 flex-1">
                <input type="date" wire:model.live="startDate"
                    class="rounded-lg px-3 py-2 text-sm focus:outline-none"
                    style="background:#ffffff;border:1px solid #e4e4e4;box-shadow:0 1px 2px rgba(21,99,223,0.04),0 8px 20px -8px rgba(21,99,223,0.06);color:#161e2d;">
                <span style="color:#a3abb0;">—</span>
                <input type="date" wire:model.live="endDate"
                    class="rounded-lg px-3 py-2 text-sm focus:outline-none"
                    style="background:#ffffff;border:1px solid #e4e4e4;box-shadow:0 1px 2px rgba(21,99,223,0.04),0 8px 20px -8px rgba(21,99,223,0.06);color:#161e2d;">
            </div>
        </div>

        {{-- Totals --}}
        <div class="flex gap-4 mt-3 pt-3" style="border-top:1px solid #ffffff;">
            <div class="flex items-center gap-2">
                <div class="w-2 h-2 rounded-full" style="background:#12805c;"></div>
                <span class="text-xs" style="color:#5c6368;">Pemasukan:</span>
                <span class="text-xs font-semibold" style="color:#12805c;">Rp {{ number_format($totalDebit, 0, ',', '.') }}</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-2 h-2 rounded-full" style="background:#c0453b;"></div>
                <span class="text-xs" style="color:#5c6368;">Pengeluaran:</span>
                <span class="text-xs font-semibold" style="color:#c0453b;">Rp {{ number_format($totalCredit, 0, ',', '.') }}</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-xs" style="color:#5c6368;">Net:</span>
                <span class="text-xs font-semibold" style="color:{{ ($totalDebit - $totalCredit) >= 0 ? '#1563df' : '#c0453b' }};">
                    Rp {{ number_format(abs($totalDebit - $totalCredit), 0, ',', '.') }}
                    {{ ($totalDebit - $totalCredit) >= 0 ? '' : '(minus)' }}
                </span>
            </div>
        </div>
    </div>

    {{-- Transaction Table --}}
    <div class="rounded-xl overflow-hidden" style="background:#ffffff;border:1px solid #f7f7f7;">
        <div class="overflow-x-auto hidden md:block">
            <table class="w-full text-sm">
                <thead>
                    <tr style="background:#ffffff;border-bottom:1px solid #f7f7f7;">
                        <th class="text-left px-4 py-3 font-semibold text-xs uppercase tracking-wider" style="color:#161e2d;">Tanggal</th>
                        <th class="text-left px-4 py-3 font-semibold text-xs uppercase tracking-wider" style="color:#161e2d;">Akun</th>
                        <th class="text-left px-4 py-3 font-semibold text-xs uppercase tracking-wider hidden md:table-cell" style="color:#161e2d;">Kategori</th>
                        <th class="text-left px-4 py-3 font-semibold text-xs uppercase tracking-wider" style="color:#161e2d;">Keterangan</th>
                        <th class="text-right px-4 py-3 font-semibold text-xs uppercase tracking-wider" style="color:#161e2d;">Jumlah</th>
                        <th class="text-center px-4 py-3 font-semibold text-xs uppercase tracking-wider" style="color:#161e2d;">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="divide-color:#ffffff;">
                    @forelse($transactions as $tx)
                    <tr class="transition-colors" style="border-bottom:1px solid #ffffff;" onmouseover="this.style.background='rgba(21,99,223,0.03)'" onmouseout="this.style.background=''">
                        <td class="px-4 py-3 whitespace-nowrap" style="color:#5c6368;">
                            {{ $tx->transaction_date->format('d M Y') }}
                        </td>
                        <td class="px-4 py-3" style="color:#161e2d;">
                            <span class="text-xs">{{ $tx->account->name ?? '-' }}</span>
                        </td>
                        <td class="px-4 py-3 hidden md:table-cell" style="color:#5c6368;">
                            <span class="text-xs">{{ $tx->category->name ?? '-' }}</span>
                            @if($tx->campaign)
                            <span class="block text-xs mt-0.5" style="color:#a3abb0;">↳ {{ $tx->campaign->name }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3" style="color:#161e2d;">
                            {{ Str::limit($tx->description, 60) }}
                        </td>
                        <td class="px-4 py-3 text-right whitespace-nowrap">
                            <span class="font-semibold text-sm" style="color:{{ $tx->type === 'debit' ? '#12805c' : '#c0453b' }};">
                                {{ $tx->type === 'debit' ? '+' : '-' }} Rp {{ number_format($tx->amount, 0, ',', '.') }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center justify-center gap-1">
                                <button wire:click="edit({{ $tx->id }})"
                                    class="p-1.5 rounded-lg transition-colors"
                                    style="color:#5c6368;" title="Edit"
                                    onmouseover="this.style.color='#1563df';this.style.background='rgba(21,99,223,0.1)'"
                                    onmouseout="this.style.color='#5c6368';this.style.background=''">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                <button wire:click="confirmDelete({{ $tx->id }})"
                                    class="p-1.5 rounded-lg transition-colors"
                                    style="color:#5c6368;" title="Hapus"
                                    onmouseover="this.style.color='#c0453b';this.style.background='rgba(192,69,59,0.1)'"
                                    onmouseout="this.style.color='#5c6368';this.style.background=''">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-12 text-center" style="color:#a3abb0;">
                            <svg class="w-10 h-10 mx-auto mb-3 opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            <p class="text-sm">Belum ada transaksi pada periode ini</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile Card List --}}
        <div class="md:hidden divide-y" style="border-color:#f7f7f7;">
            @forelse($transactions as $tx)
            <div wire:key="tx-mobile-{{ $tx->id }}" class="p-4 space-y-2">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p class="font-medium break-words" style="color:#161e2d;">{{ Str::limit($tx->description, 60) }}</p>
                        <p class="text-xs mt-0.5" style="color:#a3abb0;">{{ $tx->transaction_date->format('d M Y') }}</p>
                    </div>
                    <span class="font-mono font-semibold whitespace-nowrap" style="color:{{ $tx->type === 'debit' ? '#12805c' : '#c0453b' }};">
                        {{ $tx->type === 'debit' ? '+' : '-' }} Rp {{ number_format($tx->amount, 0, ',', '.') }}
                    </span>
                </div>
                <div class="flex flex-wrap gap-1.5">
                    @if($tx->category)
                    <span class="px-2 py-1 rounded-lg text-xs" style="background:#ffffff;border:1px solid #e4e4e4;box-shadow:0 1px 2px rgba(21,99,223,0.04),0 8px 20px -8px rgba(21,99,223,0.06);color:#5c6368;">{{ $tx->category->name }}</span>
                    @endif
                    <span class="px-2 py-1 rounded-lg text-xs" style="background:#ffffff;border:1px solid #e4e4e4;box-shadow:0 1px 2px rgba(21,99,223,0.04),0 8px 20px -8px rgba(21,99,223,0.06);color:#161e2d;">{{ $tx->account->name ?? '-' }}</span>
                    @if($tx->campaign)
                    <span class="px-2 py-1 rounded-lg text-xs" style="background:#ffffff;border:1px solid #e4e4e4;box-shadow:0 1px 2px rgba(21,99,223,0.04),0 8px 20px -8px rgba(21,99,223,0.06);color:#a3abb0;">↳ {{ $tx->campaign->name }}</span>
                    @endif
                </div>
                <div class="flex gap-2 pt-1">
                    <button wire:click="edit({{ $tx->id }})"
                        class="flex-1 px-3 py-2 rounded-lg text-xs transition-colors"
                        style="background:#ffffff;border:1px solid #e4e4e4;box-shadow:0 1px 2px rgba(21,99,223,0.04),0 8px 20px -8px rgba(21,99,223,0.06);color:#5c6368;">
                        Edit
                    </button>
                    <button wire:click="confirmDelete({{ $tx->id }})"
                        class="flex-1 px-3 py-2 rounded-lg text-xs transition-colors"
                        style="background:rgba(192,69,59,0.08);border:1px solid rgba(192,69,59,0.2);color:#c0453b;">
                        Hapus
                    </button>
                </div>
            </div>
            @empty
            <div class="px-4 py-12 text-center" style="color:#a3abb0;">
                <svg class="w-10 h-10 mx-auto mb-3 opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                <p class="text-sm">Belum ada transaksi pada periode ini</p>
            </div>
            @endforelse
        </div>

        @if($transactions->hasPages())
        <div class="px-4 py-3" style="border-top:1px solid #ffffff;">
            {{ $transactions->links() }}
        </div>
        @endif
    </div>

</div>

{{-- ===== MODAL: Create/Edit Transaction ===== --}}
@if($isModalOpen)
<div class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background:rgba(0,0,0,0.1);" wire:click.self="closeModal">
    <div class="w-full max-w-lg rounded-2xl shadow-2xl" style="background:#ffffff;border:1px solid #e4e4e4;">

        {{-- Modal Header --}}
        <div class="flex items-center justify-between px-6 py-4" style="border-bottom:1px solid #f7f7f7;">
            <h3 class="text-base font-semibold" style="color:#161e2d;">
                {{ $selectedId ? 'Edit Transaksi' : 'Tambah Transaksi' }}
            </h3>
            <button wire:click="closeModal" class="p-1.5 rounded-lg transition-colors" style="color:#a3abb0;"
                onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#a3abb0'">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Modal Body --}}
        <form wire:submit="store" class="px-6 py-5 space-y-4">

            @if(session('modal_error'))
            <div class="rounded-lg px-3 py-2 text-xs" style="background:rgba(192,69,59,0.1);border:1px solid rgba(192,69,59,0.3);color:#c0453b;">
                {{ session('modal_error') }}
            </div>
            @endif

            {{-- Jenis Transaksi --}}
            <div>
                <label class="text-xs font-medium block mb-2" style="color:#5c6368;">Jenis Transaksi</label>
                <div class="flex rounded-xl overflow-hidden" style="border:1px solid #e4e4e4;">
                    <button type="button" wire:click="$set('type', 'debit')"
                        class="flex-1 py-2 text-sm font-medium transition-all"
                        style="{{ $type === 'debit' ? 'background:rgba(74,222,128,0.15);color:#12805c;border-right:1px solid rgba(74,222,128,0.2);' : 'background:#ffffff;color:#a3abb0;border-right:1px solid #e4e4e4;' }}">
                        Pemasukan
                    </button>
                    <button type="button" wire:click="$set('type', 'credit')"
                        class="flex-1 py-2 text-sm font-medium transition-all"
                        style="{{ $type === 'credit' ? 'background:rgba(248,113,113,0.15);color:#c0453b;' : 'background:#ffffff;color:#a3abb0;' }}">
                        Pengeluaran
                    </button>
                </div>
            </div>

            {{-- Akun --}}
            <div>
                <label class="text-xs font-medium block mb-1.5" style="color:#5c6368;">Akun Kas</label>
                @if($type === 'debit')
                <div class="rounded-lg px-3 py-2 mb-2 text-xs" style="background:rgba(21,99,223,0.06);border:1px solid rgba(21,99,223,0.2);color:#161e2d;">
                    Pemasukan Kas Keamanan &amp; Kebersihan sudah dicatat otomatis dari pembayaran IPL.
                </div>
                @endif
                <select wire:model="accountId"
                    class="w-full rounded-xl px-3 py-2.5 text-sm focus:outline-none transition-colors"
                    style="background:#ffffff;border:1px solid #e4e4e4;box-shadow:0 1px 2px rgba(21,99,223,0.04),0 8px 20px -8px rgba(21,99,223,0.06);color:#161e2d;">
                    <option value="">-- Pilih Akun --</option>
                    @foreach(($type === 'debit' ? $incomeAccounts : $perumahanAccounts) as $acc)
                    <option value="{{ $acc->id }}">{{ $acc->name }}</option>
                    @endforeach
                </select>
                @error('accountId')<p class="text-xs mt-1" style="color:#c0453b;">{{ $message }}</p>@enderror
            </div>

            {{-- Kategori --}}
            <div>
                <label class="text-xs font-medium block mb-1.5" style="color:#5c6368;">Kategori</label>
                <select wire:model.live="categoryId"
                    class="w-full rounded-xl px-3 py-2.5 text-sm focus:outline-none transition-colors"
                    style="background:#ffffff;border:1px solid #e4e4e4;box-shadow:0 1px 2px rgba(21,99,223,0.04),0 8px 20px -8px rgba(21,99,223,0.06);color:#161e2d;">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($modalCategories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
                @error('categoryId')<p class="text-xs mt-1" style="color:#c0453b;">{{ $message }}</p>@enderror
            </div>

            {{-- Program (conditional) --}}
            @if($showCampaignDropdown)
            <div>
                <label class="text-xs font-medium block mb-1.5" style="color:#5c6368;">Program Terkait
                    <span class="font-normal ml-1" style="color:#a3abb0;">(Perumahan atau Masjid)</span>
                </label>
                <select wire:model="campaignId"
                    class="w-full rounded-xl px-3 py-2.5 text-sm focus:outline-none transition-colors"
                    style="background:#ffffff;border:1px solid rgba(21,99,223,0.3);color:#161e2d;">
                    <option value="">-- Pilih Program --</option>
                    @php $grouped = $availableCampaigns->groupBy('organization_type'); @endphp
                    @foreach($grouped as $orgType => $camps)
                    <optgroup label="{{ $orgType === 'dkm' ? 'Program DKM / Masjid' : 'Program Perumahan' }}">
                        @foreach($camps as $camp)
                        <option value="{{ $camp->id }}">{{ $camp->name }}</option>
                        @endforeach
                    </optgroup>
                    @endforeach
                </select>
                @error('campaignId')<p class="text-xs mt-1" style="color:#c0453b;">{{ $message }}</p>@enderror
            </div>
            @endif

            {{-- Amount + Date --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-medium block mb-1.5" style="color:#5c6368;">Jumlah (Rp)</label>
                    <input type="number" wire:model="amount" placeholder="0" min="1"
                        class="w-full rounded-xl px-3 py-2.5 text-sm focus:outline-none"
                        style="background:#ffffff;border:1px solid #e4e4e4;box-shadow:0 1px 2px rgba(21,99,223,0.04),0 8px 20px -8px rgba(21,99,223,0.06);color:#161e2d;">
                    @error('amount')<p class="text-xs mt-1" style="color:#c0453b;">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="text-xs font-medium block mb-1.5" style="color:#5c6368;">Tanggal</label>
                    <input type="date" wire:model="transactionDate"
                        class="w-full rounded-xl px-3 py-2.5 text-sm focus:outline-none"
                        style="background:#ffffff;border:1px solid #e4e4e4;box-shadow:0 1px 2px rgba(21,99,223,0.04),0 8px 20px -8px rgba(21,99,223,0.06);color:#161e2d;">
                    @error('transactionDate')<p class="text-xs mt-1" style="color:#c0453b;">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Keterangan --}}
            <div>
                <label class="text-xs font-medium block mb-1.5" style="color:#5c6368;">Keterangan</label>
                <textarea wire:model="description" rows="2" placeholder="Deskripsi transaksi..."
                    class="w-full rounded-xl px-3 py-2.5 text-sm focus:outline-none resize-none"
                    style="background:#ffffff;border:1px solid #e4e4e4;box-shadow:0 1px 2px rgba(21,99,223,0.04),0 8px 20px -8px rgba(21,99,223,0.06);color:#161e2d;"></textarea>
                @error('description')<p class="text-xs mt-1" style="color:#c0453b;">{{ $message }}</p>@enderror
            </div>

            {{-- Actions --}}
            <div class="flex gap-3 pt-2">
                <button type="submit"
                    class="flex-1 py-2.5 rounded-xl text-sm font-semibold transition-all"
                    style="background:linear-gradient(135deg,#1563df,#1563df);color:#ffffff;">
                    <span wire:loading.remove wire:target="store">{{ $selectedId ? 'Simpan Perubahan' : 'Tambah Transaksi' }}</span>
                    <span wire:loading wire:target="store">Menyimpan...</span>
                </button>
                <button type="button" wire:click="closeModal"
                    class="px-4 py-2.5 rounded-xl text-sm transition-colors"
                    style="background:#ffffff;border:1px solid #e4e4e4;box-shadow:0 1px 2px rgba(21,99,223,0.04),0 8px 20px -8px rgba(21,99,223,0.06);color:#5c6368;">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>
@endif

{{-- ===== DELETE CONFIRM MODAL ===== --}}
<div
    x-data="{ show: false, txId: null }"
    x-on:show-perumahan-tx-delete.window="show = true; txId = $event.detail.id"
    x-on:perumahan-tx-deleted.window="show = false"
    x-on:perumahan-tx-delete-failed.window="show = false">
    <div x-show="show" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background:rgba(0,0,0,0.1);">
        <div class="w-full max-w-sm rounded-2xl p-6 shadow-2xl text-center" style="background:#ffffff;border:1px solid #e4e4e4;">
            <div class="w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-4"
                style="background:rgba(192,69,59,0.1);border:1px solid rgba(192,69,59,0.3);">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="#c0453b"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
            </div>
            <h3 class="text-base font-semibold mb-2" style="color:#161e2d;">Hapus Transaksi?</h3>
            <p class="text-sm mb-5" style="color:#5c6368;">Transaksi ini akan dihapus secara permanen.</p>
            <div class="flex gap-3">
                <button @click="show=false" class="flex-1 py-2.5 rounded-xl text-sm" style="background:#ffffff;border:1px solid #e4e4e4;box-shadow:0 1px 2px rgba(21,99,223,0.04),0 8px 20px -8px rgba(21,99,223,0.06);color:#5c6368;">Batal</button>
                <button @click="$wire.delete(txId); show=false"
                    class="flex-1 py-2.5 rounded-xl text-sm font-semibold"
                    style="background:rgba(192,69,59,0.15);border:1px solid rgba(192,69,59,0.4);color:#c0453b;">
                    Hapus
                </button>
            </div>
        </div>
    </div>
</div>

</div>
