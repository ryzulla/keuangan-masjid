<div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-5">
    {{-- Header --}}
    <div class="rounded-2xl p-6 pp-hero" style="background:#ffffff;border:1px solid rgba(176,64,44,0.35);">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h3 class="font-bold text-lg" style="color:#17231E;font-family:'Fraunces',Georgia,serif;">Alert Darurat</h3>
                <p class="text-sm mt-1" style="color:#17231E;">Riwayat dan status tombol darurat dari warga.</p>
            </div>
            <div class="flex items-center gap-2">
                @php $activeCount = \App\Models\EmergencyAlert::active()->count(); @endphp
                @if($activeCount > 0)
                <button wire:click="clearAll" wire:loading.attr="disabled" x-data
                    x-on:click="if(!confirm('Hentikan semua {{ $activeCount }} alert aktif?')){event.preventDefault();}"
                    class="text-xs px-3 py-2 rounded-xl font-medium transition-all"
                    style="background:rgba(176,64,44,0.1);color:#B0402C;border:1px solid rgba(176,64,44,0.25);">
                    <span wire:loading.remove wire:target="clearAll">Hentikan Semua</span>
                    <span wire:loading wire:target="clearAll">...</span>
                </button>
                @else
                <button disabled
                    class="text-xs px-3 py-2 rounded-xl font-medium transition-all"
                    style="background:#F1F3EC;color:#909A8F;border:1px solid #D8D6C9;cursor:not-allowed;">
                    Hentikan Semua
                </button>
                @endif
                <select wire:model.live="filterStatus"
                    class="text-sm rounded-xl px-3 py-2"
                    style="background:#F1F3EC;color:#17231E;border:1px solid #D8D6C9;outline:none;">
                    <option value="all">Semua</option>
                    <option value="active">Aktif</option>
                    <option value="stopped">Dihentikan</option>
                </select>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="rounded-xl p-4 text-sm flex items-center gap-2" style="background:rgba(18,128,92,0.1);border:1px solid rgba(18,128,92,0.3);color:#12805c;">
            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Table --}}
    <div class="rounded-2xl overflow-hidden" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
        <div class="overflow-x-auto hidden md:block">
            <table class="w-full text-sm">
                <thead>
                    <tr style="border-bottom:1px solid #F1F3EC;">
                        <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Waktu</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Pelapor</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Blok</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Status</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Dihentikan Oleh</th>
                        <th class="text-right px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($alerts as $alert)
                    <tr style="border-bottom:1px solid #ffffff;" onmouseover="this.style.backgroundColor='#F1F3EC'" onmouseout="this.style.backgroundColor=''">
                        <td class="px-5 py-3.5 text-xs" style="color:#586359;">
                            {{ $alert->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="font-medium" style="color:#17231E;">{{ $alert->resident->name ?? '—' }}</span>
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="text-xs px-2 py-0.5 rounded-full font-medium"
                                style="background:rgba(176,64,44,0.1);color:#B0402C;border:1px solid rgba(176,64,44,0.25);">
                                {{ $alert->block_code }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5">
                            @if($alert->is_active)
                                <span class="text-xs px-2 py-0.5 rounded-full font-medium"
                                    style="background:rgba(176,64,44,0.1);color:#B0402C;border:1px solid rgba(176,64,44,0.25);">
                                    Aktif
                                </span>
                            @else
                                <span class="text-xs px-2 py-0.5 rounded-full font-medium"
                                    style="background:rgba(144,154,143,0.15);color:#909A8F;border:1px solid rgba(144,154,143,0.3);">
                                    Dihentikan
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-xs" style="color:#586359;">
                            {{ $alert->stopper->name ?? '—' }}
                            @if($alert->stopped_at)
                                <br><span style="color:#909A8F;">{{ $alert->stopped_at->format('d/m/Y H:i') }}</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            @if($alert->is_active)
                                <button wire:click="stopAlert({{ $alert->id }})" wire:loading.attr="disabled"
                                    class="text-xs px-2.5 py-1 rounded-lg font-medium"
                                    style="background:rgba(176,64,44,0.1);color:#B0402C;border:1px solid rgba(176,64,44,0.2);">
                                    Hentikan
                                </button>
                            @else
                                <span class="text-xs" style="color:#909A8F;">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-10 text-center">
                            <p class="text-sm" style="color:#909A8F;">Belum ada alert darurat.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile cards --}}
        <div class="md:hidden divide-y" style="border-color:#F1F3EC;">
            @forelse($alerts as $alert)
            <div class="px-4 py-3 space-y-2">
                <div class="flex items-start justify-between gap-2">
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2">
                            <span class="text-xs px-2 py-0.5 rounded-full font-medium"
                                style="background:rgba(176,64,44,0.1);color:#B0402C;border:1px solid rgba(176,64,44,0.25);">
                                {{ $alert->block_code }}
                            </span>
                            @if($alert->is_active)
                                <span class="text-xs px-2 py-0.5 rounded-full font-medium"
                                    style="background:rgba(176,64,44,0.1);color:#B0402C;border:1px solid rgba(176,64,44,0.25);">Aktif</span>
                            @else
                                <span class="text-xs px-2 py-0.5 rounded-full font-medium"
                                    style="background:rgba(144,154,143,0.15);color:#909A8F;border:1px solid rgba(144,154,143,0.3);">Dihentikan</span>
                            @endif
                        </div>
                        <p class="text-sm mt-1" style="color:#586359;">{{ $alert->resident->name ?? '—' }} · {{ $alert->created_at->format('d/m/Y H:i') }}</p>
                        @if($alert->stopper)
                            <p class="text-xs mt-0.5" style="color:#909A8F;">Dihentikan oleh {{ $alert->stopper->name }}</p>
                        @endif
                    </div>
                </div>
                @if($alert->is_active)
                <button wire:click="stopAlert({{ $alert->id }})" wire:loading.attr="disabled"
                    class="text-xs px-2.5 py-1 rounded-lg font-medium"
                    style="background:rgba(176,64,44,0.1);color:#B0402C;border:1px solid rgba(176,64,44,0.2);">Hentikan</button>
                @endif
            </div>
            @empty
            <div class="px-4 py-8 text-center">
                <p class="text-sm" style="color:#909A8F;">Belum ada alert darurat.</p>
            </div>
            @endforelse
        </div>

        <div class="px-5 py-3" style="border-top:1px solid #F1F3EC;">
            {{ $alerts->links() }}
        </div>
    </div>
</div>
