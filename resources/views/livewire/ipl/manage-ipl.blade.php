<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            IPL Security &amp; Biaya Sampah
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session()->has('success') && !$isPaymentModalOpen && !$isPeriodModalOpen)
                <div role="alert" class="alert alert-success shadow-lg mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if(session()->has('error'))
                <div role="alert" class="alert alert-error shadow-lg mb-4"><span>{{ session('error') }}</span></div>
            @endif

            {{-- Period Selector Bar --}}
            <div class="card bg-base-100 shadow mb-4">
                <div class="card-body py-3">
                    <div class="flex flex-wrap gap-3 items-center justify-between">
                        <div class="flex flex-wrap gap-2 items-center">
                            <span class="text-sm font-semibold">Periode:</span>
                            @forelse($periods as $period)
                                <button wire:click="selectPeriod({{ $period->id }})"
                                    class="btn btn-xs {{ $selectedPeriodId == $period->id ? 'btn-primary' : 'btn-outline' }}">
                                    {{ $period->period_label }}
                                    @if($period->is_closed)
                                        <span class="badge badge-xs badge-ghost ml-1">Tutup</span>
                                    @endif
                                </button>
                            @empty
                                <span class="text-sm text-gray-400">Belum ada periode.</span>
                            @endforelse
                        </div>
                        <div class="flex gap-2">
                            @if($currentPeriod && !$currentPeriod->is_closed)
                                <button wire:click="generateBillings({{ $currentPeriod->id }})"
                                    wire:confirm="Generate tagihan untuk semua penghuni aktif di periode {{ $currentPeriod->period_label }}?"
                                    class="btn btn-secondary btn-sm">
                                    <span wire:loading.remove wire:target="generateBillings">⚡ Generate Tagihan</span>
                                    <span wire:loading wire:target="generateBillings" class="loading loading-spinner loading-sm"></span>
                                </button>
                            @endif
                            <button wire:click="openCreatePeriod()" class="btn btn-primary btn-sm">+ Periode Baru</button>
                        </div>
                    </div>
                </div>
            </div>

            @if($currentPeriod)
            {{-- Period Info --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                <div class="stat bg-base-100 shadow rounded-box">
                    <div class="stat-title text-xs">Total Tagihan</div>
                    <div class="stat-value text-sm">Rp {{ number_format($summary['total_tagihan'] ?? 0, 0, ',', '.') }}</div>
                    <div class="stat-desc">{{ $summary['jumlah_lunas'] + $summary['jumlah_belum'] + $summary['jumlah_sebagian'] }} unit</div>
                </div>
                <div class="stat bg-base-100 shadow rounded-box">
                    <div class="stat-title text-xs">Sudah Terbayar</div>
                    <div class="stat-value text-sm text-success">Rp {{ number_format($summary['total_terbayar'] ?? 0, 0, ',', '.') }}</div>
                    <div class="stat-desc">{{ $summary['jumlah_lunas'] }} unit lunas</div>
                </div>
                <div class="stat bg-base-100 shadow rounded-box">
                    <div class="stat-title text-xs">Tunggakan</div>
                    <div class="stat-value text-sm text-error">Rp {{ number_format($summary['total_tunggakan'] ?? 0, 0, ',', '.') }}</div>
                    <div class="stat-desc">{{ $summary['jumlah_belum'] }} belum, {{ $summary['jumlah_sebagian'] }} sebagian</div>
                </div>
                <div class="stat bg-base-100 shadow rounded-box">
                    <div class="stat-title text-xs">Tarif Periode</div>
                    <div class="stat-value text-sm">
                        <div class="text-xs">Security: Rp {{ number_format($currentPeriod->ipl_security_amount, 0, ',', '.') }}</div>
                        <div class="text-xs">Sampah: Rp {{ number_format($currentPeriod->ipl_garbage_amount, 0, ',', '.') }}</div>
                    </div>
                    <div class="stat-desc">
                        <button wire:click="openEditPeriod({{ $currentPeriod->id }})" class="link link-primary text-xs">Edit Tarif</button>
                    </div>
                </div>
            </div>

            {{-- Filters --}}
            <div class="flex flex-wrap gap-2 mb-4">
                <select wire:model.live="filterBillingStatus" class="select select-bordered select-sm">
                    <option value="">Semua Status</option>
                    <option value="unpaid">Belum Bayar</option>
                    <option value="partial">Sebagian</option>
                    <option value="paid">Lunas</option>
                </select>
                <select wire:model.live="filterBillingBlock" class="select select-bordered select-sm">
                    <option value="">Semua Blok</option>
                    @foreach($houseBlocks as $block)
                        <option value="{{ $block->id }}">{{ $block->block_code }}</option>
                    @endforeach
                </select>
                <a href="{{ route('ipl.report') }}" wire:navigate class="btn btn-outline btn-sm">Laporan IPL</a>
            </div>

            {{-- Billings Table --}}
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <div class="overflow-x-auto">
                        <table class="table table-sm table-zebra w-full">
                            <thead>
                                <tr>
                                    <th>Blok</th>
                                    <th>Penghuni</th>
                                    <th class="text-right">Security</th>
                                    <th class="text-right">Sampah</th>
                                    <th class="text-right">Total</th>
                                    <th class="text-right">Terbayar</th>
                                    <th class="text-right">Sisa</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($billings as $billing)
                                    <tr class="hover" wire:key="billing-{{ $billing->id }}">
                                        <td>
                                            @if($billing->houseBlock)
                                                <span class="badge badge-outline badge-sm font-mono">{{ $billing->houseBlock->block_code }}</span>
                                            @else
                                                <span class="text-gray-400">—</span>
                                            @endif
                                        </td>
                                        <td class="font-medium">{{ $billing->resident?->name ?? '—' }}</td>
                                        <td class="text-right font-mono text-xs">Rp {{ number_format($billing->ipl_security_amount, 0, ',', '.') }}</td>
                                        <td class="text-right font-mono text-xs">Rp {{ number_format($billing->ipl_garbage_amount, 0, ',', '.') }}</td>
                                        <td class="text-right font-mono text-sm font-semibold">Rp {{ number_format($billing->total_amount, 0, ',', '.') }}</td>
                                        <td class="text-right font-mono text-xs text-success">Rp {{ number_format($billing->total_paid, 0, ',', '.') }}</td>
                                        <td class="text-right font-mono text-xs {{ $billing->outstanding > 0 ? 'text-error' : '' }}">
                                            Rp {{ number_format($billing->outstanding, 0, ',', '.') }}
                                        </td>
                                        <td>
                                            <span @class([
                                                'badge badge-sm',
                                                'badge-success' => $billing->status === 'paid',
                                                'badge-warning' => $billing->status === 'partial',
                                                'badge-error' => $billing->status === 'unpaid',
                                            ])>
                                                {{ $billing->status === 'paid' ? 'Lunas' : ($billing->status === 'partial' ? 'Sebagian' : 'Belum') }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($billing->status !== 'paid')
                                                <button wire:click="openPayment({{ $billing->id }})" class="btn btn-primary btn-xs">Bayar</button>
                                            @else
                                                <span class="text-success text-xs">✓ Lunas</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="9" class="text-center py-8 text-gray-400">
                                        Belum ada tagihan untuk periode ini.
                                        @if($currentPeriod && !$currentPeriod->is_closed)
                                            <br><span class="text-xs">Klik "Generate Tagihan" untuk membuat tagihan otomatis.</span>
                                        @endif
                                    </td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">{{ $billings->links() }}</div>
                </div>
            </div>
            @else
                <div class="alert alert-info">
                    <span>Pilih atau buat periode IPL terlebih dahulu.</span>
                </div>
            @endif

        </div>
    </div>

    {{-- Period Modal --}}
    <div class="modal {{ $isPeriodModalOpen ? 'modal-open' : '' }}" x-data x-on:keydown.escape.window="$wire.closePeriodModal()">
        <div class="modal-box" @click.stop>
            <h3 class="font-bold text-lg">{{ $editingPeriodId ? 'Edit Periode IPL' : 'Buat Periode IPL Baru' }}</h3>

            @if(session()->has('modal_error'))
                <div class="alert alert-error mt-3"><span>{{ session('modal_error') }}</span></div>
            @endif

            <form wire:submit="savePeriod" class="space-y-4 mt-4">
                <div class="grid grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label"><span class="label-text">Tahun <span class="text-error">*</span></span></label>
                        <input type="number" wire:model="periodYear" class="input input-bordered w-full" min="2020" max="2100">
                        @error('periodYear')<span class="text-error text-xs">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text">Bulan <span class="text-error">*</span></span></label>
                        <select wire:model="periodMonth" class="select select-bordered w-full">
                            @foreach(['1'=>'Januari','2'=>'Februari','3'=>'Maret','4'=>'April','5'=>'Mei','6'=>'Juni','7'=>'Juli','8'=>'Agustus','9'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'] as $v => $l)
                                <option value="{{ $v }}">{{ $l }}</option>
                            @endforeach
                        </select>
                        @error('periodMonth')<span class="text-error text-xs">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label"><span class="label-text">Iuran Security (Rp) <span class="text-error">*</span></span></label>
                        <input type="number" wire:model="periodSecurityAmount" class="input input-bordered w-full" min="0" step="1000">
                        @error('periodSecurityAmount')<span class="text-error text-xs">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text">Biaya Sampah (Rp) <span class="text-error">*</span></span></label>
                        <input type="number" wire:model="periodGarbageAmount" class="input input-bordered w-full" min="0" step="1000">
                        @error('periodGarbageAmount')<span class="text-error text-xs">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Catatan</span></label>
                    <textarea wire:model="periodNotes" class="textarea textarea-bordered" rows="2"></textarea>
                </div>
                @if($editingPeriodId)
                <div class="form-control">
                    <label class="label cursor-pointer">
                        <span class="label-text">Tutup Periode (tidak bisa tambah tagihan)</span>
                        <input type="checkbox" wire:model="periodIsClosed" class="toggle toggle-error">
                    </label>
                </div>
                @endif
                <div class="modal-action">
                    <button type="button" wire:click="closePeriodModal()" class="btn btn-ghost">Batal</button>
                    <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                        <span wire:loading.remove>Simpan</span>
                        <span wire:loading class="loading loading-spinner loading-sm"></span>
                    </button>
                </div>
            </form>
        </div>
        <form wire:click="closePeriodModal" class="modal-backdrop"><button type="button">close</button></form>
    </div>

    {{-- Payment Modal --}}
    <div class="modal {{ $isPaymentModalOpen ? 'modal-open' : '' }}" x-data x-on:keydown.escape.window="$wire.closePaymentModal()">
        <div class="modal-box" @click.stop>
            @if($payingBilling)
                <h3 class="font-bold text-lg">Catat Pembayaran IPL</h3>
                <div class="bg-base-200 rounded-lg p-3 mt-3 text-sm">
                    <div class="font-semibold">{{ $payingBilling->resident?->name ?? '—' }}</div>
                    <div>Blok: {{ $payingBilling->houseBlock?->block_code ?? '—' }}</div>
                    <div class="mt-1 grid grid-cols-2 gap-1">
                        <div>Security: Rp {{ number_format($payingBilling->ipl_security_amount, 0, ',', '.') }}</div>
                        <div>Sampah: Rp {{ number_format($payingBilling->ipl_garbage_amount, 0, ',', '.') }}</div>
                        <div class="text-success">Terbayar Security: Rp {{ number_format($payingBilling->paid_security, 0, ',', '.') }}</div>
                        <div class="text-success">Terbayar Sampah: Rp {{ number_format($payingBilling->paid_garbage, 0, ',', '.') }}</div>
                    </div>
                </div>
            @endif

            @if($errors->any() || session()->has('modal_error'))
                <div class="alert alert-warning mt-3">
                    @if(session()->has('modal_error'))
                        <span>{{ session('modal_error') }}</span>
                    @else
                        <ul class="list-disc pl-4 text-xs">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                    @endif
                </div>
            @endif

            <form wire:submit="savePayment" class="space-y-3 mt-4">
                <div class="form-control">
                    <label class="label"><span class="label-text">Tanggal Bayar <span class="text-error">*</span></span></label>
                    <input type="date" wire:model="paymentDate" class="input input-bordered w-full">
                    @error('paymentDate')<span class="text-error text-xs">{{ $message }}</span>@enderror
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div class="form-control">
                        <label class="label"><span class="label-text">Jumlah Security (Rp) <span class="text-error">*</span></span></label>
                        <input type="number" wire:model="paymentAmountSecurity" class="input input-bordered w-full" min="0" step="1000">
                        @error('paymentAmountSecurity')<span class="text-error text-xs">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text">Jumlah Sampah (Rp) <span class="text-error">*</span></span></label>
                        <input type="number" wire:model="paymentAmountGarbage" class="input input-bordered w-full" min="0" step="1000">
                        @error('paymentAmountGarbage')<span class="text-error text-xs">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div class="form-control">
                        <label class="label"><span class="label-text">Metode Pembayaran</span></label>
                        <select wire:model="paymentMethod" class="select select-bordered w-full">
                            <option value="cash">Cash / Tunai</option>
                            <option value="transfer">Transfer Bank</option>
                            <option value="other">Lainnya</option>
                        </select>
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text">Akun Penerimaan</span></label>
                        <select wire:model="paymentAccountId" class="select select-bordered w-full">
                            <option value="">-- Pilih Akun --</option>
                            @foreach($perumahanAccounts as $acc)
                                <option value="{{ $acc->id }}">{{ $acc->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div class="form-control">
                        <label class="label"><span class="label-text">No. Referensi</span></label>
                        <input type="text" wire:model="paymentReference" class="input input-bordered w-full" placeholder="Opsional">
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text">Diterima Oleh</span></label>
                        <input type="text" wire:model="paymentReceivedBy" class="input input-bordered w-full" placeholder="Nama penerima">
                    </div>
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Catatan</span></label>
                    <textarea wire:model="paymentNotes" class="textarea textarea-bordered" rows="2"></textarea>
                </div>
                <div class="modal-action">
                    <button type="button" wire:click="closePaymentModal()" class="btn btn-ghost">Batal</button>
                    <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                        <span wire:loading.remove>Simpan Pembayaran</span>
                        <span wire:loading class="loading loading-spinner loading-sm"></span>
                    </button>
                </div>
            </form>
        </div>
        <form wire:click="closePaymentModal" class="modal-backdrop"><button type="button">close</button></form>
    </div>
</div>
