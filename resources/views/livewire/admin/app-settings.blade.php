<div>
    <div class="py-6 max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

        @if(session()->has('success'))
            <div class="mb-4 rounded-xl p-3.5 text-sm flex items-center gap-2" style="background:rgba(18,128,92,0.1);border:1px solid rgba(18,128,92,0.3);color:#12805c;">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- Header --}}
        <div class="rounded-2xl p-6 mb-5" style="background:#ffffff;border:1px solid rgba(22,74,64,0.35);">
            <h3 class="font-bold text-lg" style="color:#17231E;font-family:'Fraunces',Georgia,serif;">Pengaturan Aplikasi</h3>
            <p class="text-sm mt-1" style="color:#586359;">Atur nama aplikasi dan aktif/nonaktifkan modul Perumahan &amp; DKM.</p>
        </div>

        <form wire:submit="save" class="space-y-5">

            {{-- Identitas Aplikasi --}}
            <div class="rounded-2xl p-6" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04);">
                <p class="text-xs font-semibold uppercase tracking-wider mb-4" style="color:#909A8F;">Identitas Aplikasi</p>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color:#586359;">Nama Perumahan / Aplikasi</label>
                        <input type="text" wire:model="appName" placeholder="Sistem Perumahan"
                            style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;"
                            onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                        <p class="text-xs mt-1" style="color:#909A8F;">Tampil di judul tab, brand navbar, portal penghuni, dan halaman publik.</p>
                        @error('appName') <p class="text-xs mt-1" style="color:#B0402C;">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color:#586359;">Subtitle / Tagline</label>
                        <input type="text" wire:model="appSubtitle" placeholder="Sistem Manajemen Perumahan & DKM"
                            style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;"
                            onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                        @error('appSubtitle') <p class="text-xs mt-1" style="color:#B0402C;">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Halaman Utama (Publik) --}}
            <div class="rounded-2xl p-6" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04);">
                <p class="text-xs font-semibold uppercase tracking-wider mb-1" style="color:#909A8F;">Halaman Utama (Publik)</p>
                <p class="text-xs mb-4" style="color:#909A8F;">Judul besar &amp; deskripsi yang tampil di halaman depan untuk warga. Kosongkan untuk memakai bawaan.</p>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color:#586359;">Judul Halaman Utama</label>
                        <input type="text" wire:model="homeTitle" placeholder="{{ \App\Models\Setting::appName() }}"
                            style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;"
                            onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                        <p class="text-xs mt-1" style="color:#909A8F;">Jika kosong, memakai nama aplikasi.</p>
                        @error('homeTitle') <p class="text-xs mt-1" style="color:#B0402C;">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color:#586359;">Deskripsi / Tagline Halaman Utama</label>
                        <textarea wire:model="homeTagline" rows="2" placeholder="Papan transparansi keuangan & program perumahan dan DKM Masjid — terbuka untuk seluruh warga."
                            style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;resize:vertical;"
                            onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'"></textarea>
                        @error('homeTagline') <p class="text-xs mt-1" style="color:#B0402C;">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Modul --}}
            <div class="rounded-2xl p-6" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04);">
                <p class="text-xs font-semibold uppercase tracking-wider mb-1" style="color:#909A8F;">Modul Aktif</p>
                <p class="text-xs mb-4" style="color:#909A8F;">Menonaktifkan modul akan menyembunyikan menunya dan menutup akses halamannya.</p>

                <div class="space-y-3">
                    {{-- Perumahan --}}
                    <div class="flex items-center justify-between gap-4 rounded-xl px-4 py-3.5" style="border:1px solid #E0DFD4;background:#F5F7F1;">
                        <div class="flex items-center gap-3">
                            <span style="width:36px;height:36px;border-radius:10px;background:rgba(22,74,64,0.1);display:flex;align-items:center;justify-content:center;">
                                <svg class="w-5 h-5" style="color:#164A40" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            </span>
                            <div>
                                <p class="text-sm font-semibold" style="color:#17231E;">Modul Perumahan</p>
                                <p class="text-xs" style="color:#909A8F;">Data Penghuni, Blok Rumah, IPL, Transaksi Perumahan</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <span class="text-xs font-semibold" style="color:{{ $modulePerumahan ? '#12805c' : '#909A8F' }};">{{ $modulePerumahan ? 'Aktif' : 'Nonaktif' }}</span>
                            <button type="button" wire:click="$toggle('modulePerumahan')" role="switch" aria-checked="{{ $modulePerumahan ? 'true' : 'false' }}"
                                class="relative rounded-full transition-colors" style="width:46px;height:26px;padding:3px;background:{{ $modulePerumahan ? '#164A40' : '#C9CDBF' }};">
                                <span class="block rounded-full bg-white transition-transform" style="width:20px;height:20px;transform:{{ $modulePerumahan ? 'translateX(20px)' : 'translateX(0)' }};"></span>
                            </button>
                        </div>
                    </div>

                    {{-- DKM --}}
                    <div class="flex items-center justify-between gap-4 rounded-xl px-4 py-3.5" style="border:1px solid #E0DFD4;background:#F5F7F1;">
                        <div class="flex items-center gap-3">
                            <span style="width:36px;height:36px;border-radius:10px;background:rgba(22,74,64,0.1);display:flex;align-items:center;justify-content:center;">
                                <svg class="w-5 h-5" style="color:#164A40" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </span>
                            <div>
                                <p class="text-sm font-semibold" style="color:#17231E;">Modul DKM Masjid</p>
                                <p class="text-xs" style="color:#909A8F;">Transaksi DKM &amp; keuangan masjid</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <span class="text-xs font-semibold" style="color:{{ $moduleDkm ? '#12805c' : '#909A8F' }};">{{ $moduleDkm ? 'Aktif' : 'Nonaktif' }}</span>
                            <button type="button" wire:click="$toggle('moduleDkm')" role="switch" aria-checked="{{ $moduleDkm ? 'true' : 'false' }}"
                                class="relative rounded-full transition-colors" style="width:46px;height:26px;padding:3px;background:{{ $moduleDkm ? '#164A40' : '#C9CDBF' }};">
                                <span class="block rounded-full bg-white transition-transform" style="width:20px;height:20px;transform:{{ $moduleDkm ? 'translateX(20px)' : 'translateX(0)' }};"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit"
                    class="px-5 py-2.5 text-sm rounded-xl font-semibold transition-colors"
                    style="background:#164A40;color:#ffffff;"
                    onmouseover="this.style.background='#0F3A32'" onmouseout="this.style.background='#164A40'"
                    wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="save">Simpan Pengaturan</span>
                    <span wire:loading wire:target="save">Menyimpan...</span>
                </button>
            </div>
        </form>

    </div>
</div>
