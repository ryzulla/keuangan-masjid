<div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-5">
    {{-- Header --}}
    <div class="rounded-2xl p-6 pp-hero" style="background:#ffffff;border:1px solid rgba(22,74,64,0.35);">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h3 class="font-bold text-lg" style="color:#17231E;font-family:'Fraunces',Georgia,serif;">Laporan Warga</h3>
                <p class="text-sm mt-1" style="color:#17231E;">Kelola laporan kondisi warga dari penghuni.</p>
            </div>
            <div class="flex items-center gap-2">
                <select wire:model.live="filterStatus"
                    class="text-sm rounded-xl px-3 py-2"
                    style="background:#F1F3EC;color:#17231E;border:1px solid #D8D6C9;outline:none;">
                    <option value="all">Semua</option>
                    <option value="pending">Pending</option>
                    <option value="published">Published</option>
                    <option value="dismissed">Diabaikan</option>
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
                        <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Pelapor</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Kategori</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Untuk</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Status</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Tanggal</th>
                        <th class="text-right px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $report)
                    <tr style="border-bottom:1px solid #ffffff;" onmouseover="this.style.backgroundColor='#F1F3EC'" onmouseout="this.style.backgroundColor=''">
                        <td class="px-5 py-3.5">
                            <span class="font-medium" style="color:#17231E;">{{ $report->resident->name ?? '—' }}</span>
                        </td>
                        <td class="px-5 py-3.5">
                            @php
                                $catStyle = match($report->category) {
                                    'sakit'     => 'background:rgba(169,116,26,0.1);color:#A9741A;border:1px solid rgba(169,116,26,0.25);',
                                    'meninggal' => 'background:rgba(176,64,44,0.1);color:#B0402C;border:1px solid rgba(176,64,44,0.25);',
                                    default     => 'background:rgba(22,74,64,0.1);color:#164A40;border:1px solid rgba(22,74,64,0.25);',
                                };
                                $catLabel = match($report->category) {
                                    'sakit'     => 'Sakit',
                                    'meninggal' => 'Berita Duka',
                                    default     => 'Lainnya',
                                };
                            @endphp
                            <span class="text-xs px-2 py-0.5 rounded-full font-medium" style="{{ $catStyle }}">{{ $catLabel }}</span>
                        </td>
                        <td class="px-5 py-3.5 text-xs" style="color:#586359;">
                            {{ $report->person_name ?? $report->resident->name ?? '—' }}
                        </td>
                        <td class="px-5 py-3.5">
                            @php
                                $statusStyle = match($report->status) {
                                    'pending'   => 'background:rgba(169,116,26,0.1);color:#A9741A;border:1px solid rgba(169,116,26,0.25);',
                                    'published' => 'background:rgba(18,128,92,0.1);color:#12805c;border:1px solid rgba(18,128,92,0.25);',
                                    default     => 'background:rgba(144,154,143,0.15);color:#909A8F;border:1px solid rgba(144,154,143,0.3);',
                                };
                                $statusLabel = match($report->status) {
                                    'pending'   => 'Pending',
                                    'published' => 'Published',
                                    default     => 'Diabaikan',
                                };
                            @endphp
                            <span class="text-xs px-2 py-0.5 rounded-full font-medium" style="{{ $statusStyle }}">{{ $statusLabel }}</span>
                        </td>
                        <td class="px-5 py-3.5 text-xs" style="color:#586359;">
                            {{ $report->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @if($report->status === 'pending')
                                    <button wire:click="openPublishModal({{ $report->id }})"
                                        class="text-xs px-2.5 py-1 rounded-lg font-medium"
                                        style="background:rgba(18,128,92,0.1);color:#12805c;border:1px solid rgba(18,128,92,0.2);">
                                        Publish
                                    </button>
                                    <button wire:click="dismiss({{ $report->id }})"
                                        class="text-xs px-2.5 py-1 rounded-lg font-medium"
                                        style="background:rgba(144,154,143,0.15);color:#909A8F;border:1px solid rgba(144,154,143,0.3);">
                                        Abaikan
                                    </button>
                                @endif
                                <button wire:click="delete({{ $report->id }})" wire:confirm="Yakin hapus laporan ini?"
                                    class="text-xs px-2.5 py-1 rounded-lg font-medium"
                                    style="background:rgba(176,64,44,0.1);color:#B0402C;border:1px solid rgba(176,64,44,0.2);">
                                    Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-10 text-center">
                            <p class="text-sm" style="color:#909A8F;">Belum ada laporan warga.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile cards --}}
        <div class="md:hidden divide-y" style="border-color:#F1F3EC;">
            @forelse($reports as $report)
            <div class="px-4 py-3 space-y-2">
                <div class="flex items-start justify-between gap-2">
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium" style="color:#17231E;">{{ $report->resident->name ?? '—' }}</p>
                        <p class="text-xs mt-0.5" style="color:#909A8F;">
                            {{ $report->category }} · {{ $report->person_name ?? $report->resident->name ?? '—' }}
                        </p>
                        <p class="text-xs mt-1 line-clamp-2" style="color:#586359;">{{ $report->description }}</p>
                        <p class="text-[10px] mt-1" style="color:#909A8F;">{{ $report->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    @if($report->status === 'pending')
                        <button wire:click="openPublishModal({{ $report->id }})"
                            class="text-xs px-2.5 py-1 rounded-lg font-medium"
                            style="background:rgba(18,128,92,0.1);color:#12805c;border:1px solid rgba(18,128,92,0.2);">Publish</button>
                        <button wire:click="dismiss({{ $report->id }})"
                            class="text-xs px-2.5 py-1 rounded-lg font-medium"
                            style="background:rgba(144,154,143,0.15);color:#909A8F;border:1px solid rgba(144,154,143,0.3);">Abaikan</button>
                    @endif
                    <button wire:click="delete({{ $report->id }})" wire:confirm="Yakin hapus?"
                        class="text-xs px-2.5 py-1 rounded-lg font-medium"
                        style="background:rgba(176,64,44,0.1);color:#B0402C;border:1px solid rgba(176,64,44,0.2);">Hapus</button>
                </div>
            </div>
            @empty
            <div class="px-4 py-8 text-center">
                <p class="text-sm" style="color:#909A8F;">Belum ada laporan warga.</p>
            </div>
            @endforelse
        </div>

        <div class="px-5 py-3" style="border-top:1px solid #F1F3EC;">
            {{ $reports->links() }}
        </div>
    </div>

    {{-- Publish Modal --}}
    @if($isPublishModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0" style="background:rgba(0,0,0,0.1);backdrop-filter:blur(4px);" wire:click="$set('isPublishModalOpen', false)"></div>
        <div class="relative rounded-2xl shadow-2xl w-full max-w-lg" style="background:#ffffff;border:1px solid #D8D6C9;">
            <div class="px-6 py-4 rounded-t-2xl" style="background:#F1F3EC;border-bottom:1px solid rgba(22,74,64,0.35);">
                <h3 class="font-bold" style="color:#17231E;font-family:'Fraunces',Georgia,serif;">Publikasikan sebagai Pengumuman</h3>
            </div>
            <div class="px-6 py-5 space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1.5" style="color:#586359;">Judul</label>
                    <input type="text" wire:model="publishTitle"
                        style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.625rem 0.875rem;width:100%;font-size:0.875rem;outline:none;">
                    @error('publishTitle') <p class="text-xs mt-1" style="color:#B0402C;">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1.5" style="color:#586359;">Isi Pengumuman</label>
                    <textarea wire:model="publishContent" rows="5"
                        style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.625rem 0.875rem;width:100%;font-size:0.875rem;outline:none;resize:none;"></textarea>
                    @error('publishContent') <p class="text-xs mt-1" style="color:#B0402C;">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1.5" style="color:#586359;">Prioritas</label>
                    <select wire:model="publishPriority"
                        style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.625rem 0.875rem;width:100%;font-size:0.875rem;outline:none;">
                        <option value="info">Info</option>
                        <option value="warning">Peringatan</option>
                        <option value="urgent">Mendesak</option>
                    </select>
                </div>
            </div>
            <div class="px-6 py-4 flex justify-end gap-3" style="border-top:1px solid #E0DFD4;">
                <button type="button" wire:click="$set('isPublishModalOpen', false)"
                    class="px-4 py-2 text-sm rounded-xl font-medium"
                    style="background:#F1F3EC;color:#17231E;border:1px solid #D8D6C9;">Batal</button>
                <button wire:click="publishAsNotice" wire:loading.attr="disabled"
                    class="px-5 py-2 text-sm rounded-xl font-semibold"
                    style="background:#164A40;color:#ffffff;">
                    <span wire:loading.remove wire:target="publishAsNotice">Publikasikan</span>
                    <span wire:loading wire:target="publishAsNotice">Memproses...</span>
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
