<div>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8 px-4 space-y-6">

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="rounded-xl p-3.5 text-sm flex items-center gap-2" style="background:rgba(18,128,92,0.1);border:1px solid rgba(18,128,92,0.3);color:#12805c;">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="rounded-xl p-3.5 text-sm flex items-center gap-2" style="background:rgba(176,64,44,0.1);border:1px solid rgba(176,64,44,0.3);color:#B0402C;">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01"/></svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- Header Banner --}}
        <div class="rounded-2xl p-6 pp-hero" style="background:#ffffff;border:1px solid rgba(22,74,64,0.35);">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold" style="color:#17231E;font-family:'Fraunces',Georgia,serif;">Program &amp; Kampanye</h1>
                    <p class="text-sm mt-1" style="color:#17231E;">Kelola program donasi dan kegiatan perumahan &amp; DKM</p>
                </div>
                <a href="{{ route('campaigns.create', ['org' => $activeOrgTab]) }}" wire:navigate
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold transition-colors shrink-0"
                    style="background:#164A40;color:#ffffff;"
                    onmouseover="this.style.background='#0F3A32'" onmouseout="this.style.background='#0F3A32'">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Program
                </a>
            </div>
        </div>

        {{-- Org Tabs (hanya bila boleh mengurus lebih dari satu org) --}}
        @if(count($allowedOrgs) > 1)
        <div class="flex gap-1 p-1 rounded-xl w-fit" style="background:#ffffff;border:1px solid #E0DFD4;">
            <button wire:click="switchTab('dkm')"
                class="px-5 py-2 rounded-lg text-sm font-medium transition-all"
                style="{{ $activeOrgTab === 'dkm' ? 'background:#164A40;color:#ffffff;' : 'color:#909A8F;' }}"
                @if($activeOrgTab !== 'dkm') onmouseover="this.style.color='#164A40'" onmouseout="this.style.color='#909A8F'" @endif>
                Program DKM Masjid
            </button>
            <button wire:click="switchTab('perumahan')"
                class="px-5 py-2 rounded-lg text-sm font-medium transition-all"
                style="{{ $activeOrgTab === 'perumahan' ? 'background:#164A40;color:#ffffff;' : 'color:#909A8F;' }}"
                @if($activeOrgTab !== 'perumahan') onmouseover="this.style.color='#164A40'" onmouseout="this.style.color='#909A8F'" @endif>
                Program Perumahan
            </button>
        </div>
        @endif

        {{-- Card Grid --}}
        @if($campaigns->isEmpty())
            <div class="rounded-2xl px-4 py-16 text-center" style="background:rgba(22,74,64,0.03);border:1px dashed rgba(22,74,64,0.2);">
                <svg class="w-14 h-14 mx-auto mb-3 opacity-20" fill="none" viewBox="0 0 24 24" stroke="#164A40"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                <p class="text-sm font-medium" style="color:#909A8F;">Belum ada program {{ $activeOrgTab === 'perumahan' ? 'Perumahan' : 'DKM' }}.</p>
                <p class="text-xs mt-1 mb-4" style="color:#909A8F;">Klik "Tambah Program" untuk membuat program baru.</p>
                <a href="{{ route('campaigns.create', ['org' => $activeOrgTab]) }}" wire:navigate
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold"
                    style="background:#164A40;color:#ffffff;">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Buat Program Pertama
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($campaigns->items() as $campaign)
                    <div class="rounded-2xl overflow-hidden flex flex-col" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);" wire:key="campaign-card-{{ $campaign->id }}">
                        {{-- Thumbnail --}}
                        <div class="relative" style="aspect-ratio:16/9;overflow:hidden;background:#ffffff;">
                            @if($campaign->image)
                                <img src="{{ Storage::url($campaign->image) }}" alt="{{ $campaign->name }}"
                                     class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">
                            @else
                                <div class="w-full h-full flex items-center justify-center" style="background:linear-gradient(135deg,#ffffff,#ffffff);">
                                    <svg class="w-12 h-12 opacity-20" fill="none" viewBox="0 0 24 24" stroke="#164A40"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                            @endif
                            <div class="absolute top-2 left-2 flex gap-1.5">
                                <span class="px-2 py-0.5 rounded-full text-xs font-semibold"
                                    style="{{ $campaign->status === 'active' ? 'background:rgba(18,128,92,0.9);color:#17231E;' : ($campaign->status === 'completed' ? 'background:rgba(22,74,64,0.9);color:#17231E;' : 'background:rgba(176,64,44,0.9);color:#17231E;') }}">
                                    {{ $campaign->status === 'active' ? 'Aktif' : ($campaign->status === 'completed' ? 'Selesai' : 'Dibatalkan') }}
                                </span>
                            </div>
                            @if($campaign->photos->count() > 0)
                                <div class="absolute top-2 right-2 px-1.5 py-0.5 rounded-lg text-xs flex items-center gap-1" style="background:rgba(0,0,0,0.1);color:#17231E;">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14"/></svg>
                                    {{ $campaign->photos->count() }}
                                </div>
                            @endif
                        </div>

                        {{-- Card body --}}
                        <div class="flex-1 p-4 flex flex-col gap-3">
                            <div>
                                <h3 class="font-bold text-sm leading-tight line-clamp-2" style="color:#17231E;">{{ $campaign->name }}</h3>
                                @if($campaign->description)
                                    <p class="text-xs mt-1.5 leading-relaxed line-clamp-2" style="color:#909A8F;">{{ $campaign->description }}</p>
                                @endif
                            </div>

                            {{-- Progress --}}
                            @php
                                $target   = (float)($campaign->target_amount ?? 0);
                                $raised   = (float)($campaign->transactions_sum_amount ?? 0);
                                $progress = $target > 0 ? min(100, round($raised / $target * 100)) : ($raised > 0 ? 100 : 0);
                            @endphp
                            <div>
                                <div class="flex justify-between text-xs mb-1" style="color:#909A8F;">
                                    <span style="color:#12805c;font-weight:600;">Rp {{ number_format($raised, 0, ',', '.') }}</span>
                                    <span>{{ $progress }}%</span>
                                </div>
                                <div class="w-full rounded-full h-1.5" style="background:#E0DFD4;">
                                    <div class="h-1.5 rounded-full" style="width:{{ $progress }}%;background:{{ $progress >= 100 ? '#12805c' : 'linear-gradient(90deg,#164A40,#164A40)' }};"></div>
                                </div>
                                @if($target > 0)
                                    <div class="text-right text-xs mt-0.5" style="color:#909A8F;">Target: Rp {{ number_format($target, 0, ',', '.') }}</div>
                                @endif
                            </div>

                            {{-- Meta --}}
                            <div class="flex flex-wrap items-center gap-1.5 text-xs" style="color:#909A8F;">
                                <span>{{ optional($campaign->start_date)->format('d M Y') }}</span>
                                @if($campaign->end_date)
                                    <span>→ {{ optional($campaign->end_date)->format('d M Y') }}</span>
                                @endif
                                @if($campaign->location)
                                    <span class="flex items-center gap-0.5">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                        {{ Str::limit($campaign->location, 20) }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Card Footer --}}
                        <div class="px-4 pb-4 flex gap-2">
                            <a href="{{ route('campaigns.show', $campaign) }}" wire:navigate
                               class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl text-xs font-semibold transition-colors"
                               style="background:#164A40;color:#ffffff;"
                               onmouseover="this.style.background='#0F3A32'" onmouseout="this.style.background='#0F3A32'">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                Lihat Detail
                            </a>
                            <a href="{{ route('campaigns.edit', $campaign) }}" wire:navigate
                                class="px-3 py-2 rounded-xl text-xs font-medium transition-colors"
                                style="background:rgba(22,74,64,0.1);color:#17231E;border:1px solid rgba(22,74,64,0.2);"
                                onmouseover="this.style.background='rgba(22,74,64,0.2)'" onmouseout="this.style.background='rgba(22,74,64,0.1)'">
                                Edit
                            </a>
                            <button wire:click.prevent="confirmDelete({{ $campaign->id }})"
                                class="px-3 py-2 rounded-xl text-xs font-medium transition-colors"
                                style="background:rgba(176,64,44,0.1);color:#B0402C;border:1px solid rgba(176,64,44,0.2);"
                                onmouseover="this.style.background='rgba(176,64,44,0.2)'" onmouseout="this.style.background='rgba(176,64,44,0.1)'">
                                Hapus
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($campaigns->hasPages())
                <div class="px-2 py-3">{{ $campaigns->links() }}</div>
            @endif
        @endif

    </div>

    @push('scripts')
    <script>
    (function() {
        let listenersAttached = false;
        function initListeners() {
            if (!listenersAttached && window.Livewire && window.Swal) {
                Livewire.on('show-campaign-delete-confirmation', (event) => {
                    let id = event.id ?? (event[0]?.id);
                    Swal.fire({
                        title: 'Anda Yakin?', text: "Program ini akan dihapus permanen!",
                        icon: 'warning', showCancelButton: true, confirmButtonColor: '#164A40',
                        cancelButtonColor: '#B0402C', confirmButtonText: 'Ya, Hapus!', cancelButtonText: 'Batal',
                        background: '#ffffff', color: '#17231E',
                    }).then((result) => {
                        if (result.isConfirmed && id !== undefined) @this.call('delete', id);
                    });
                });
                Livewire.on('campaignDeleted', () => Swal.fire({ title: 'Berhasil!', text: 'Program berhasil dihapus.', icon: 'success', timer: 2000, showConfirmButton: false, background: '#ffffff', color: '#17231E' }));
                Livewire.on('deleteFailed', (e) => Swal.fire('Gagal!', e.message ?? (e[0]?.message ?? 'Gagal menghapus.'), 'error'));
                listenersAttached = true;
            }
        }
        document.addEventListener('livewire:navigated', () => { listenersAttached = false; initListeners(); });
        document.addEventListener('livewire:initialized', initListeners);
    })();
    </script>
    @endpush
</div>
