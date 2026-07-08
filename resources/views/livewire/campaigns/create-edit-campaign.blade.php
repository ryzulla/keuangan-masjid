<div>
    <x-slot name="header">
        <h2 class="font-semibold text-base" style="color:#111827;">{{ $pageTitle }}</h2>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto sm:px-6 lg:px-8 px-4 space-y-6">

        {{-- Flash --}}
        @if(session('error'))
        <div class="rounded-xl p-3.5 text-sm flex items-center gap-2" style="background:rgba(192,69,59,0.1);border:1px solid rgba(192,69,59,0.3);color:#c0453b;">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01"/></svg>
            {{ session('error') }}
        </div>
        @endif

        {{-- Breadcrumb / Back --}}
        <div class="flex items-center gap-3">
            <button wire:click="cancel" class="flex items-center gap-1.5 text-sm transition-colors" style="color:#667085;"
                onmouseover="this.style.color='#111827'" onmouseout="this.style.color='#667085'">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Kembali ke Daftar Program
            </button>
            <span style="color:#98a2b3;">/</span>
            <span class="text-sm font-semibold" style="color:#111827;">{{ $pageTitle }}</span>
        </div>

        {{-- Page Header --}}
        <div class="rounded-2xl p-6" style="background:linear-gradient(135deg,#ffffff 0%,#ffffff 62%);border:1px solid rgba(16,24,40,0.35);">
            <div class="flex items-center gap-3">
                <div>
                    <h1 class="text-xl font-bold" style="color:#111827;font-family:'IBM Plex Sans',serif;">{{ $pageTitle }}</h1>
                    <p class="text-xs mt-1" style="color:#111827;">
                        {{ $campaignId ? 'Perbarui informasi dan konten program' : 'Isi detail untuk membuat program baru' }}
                    </p>
                </div>
                <span class="ml-auto px-3 py-1 rounded-full text-xs font-semibold"
                    style="{{ $organization_type === 'perumahan' ? 'background:rgba(16,24,40,0.15);color:#111827;border:1px solid rgba(16,24,40,0.3);' : 'background:rgba(18,128,92,0.12);color:#12805c;border:1px solid rgba(18,128,92,0.25);' }}">
                    {{ $organization_type === 'perumahan' ? 'Perumahan' : 'DKM Masjid' }}
                </span>
            </div>
        </div>

        {{-- Error Summary --}}
        @if($errors->any())
        <div class="rounded-xl p-4 text-sm" style="background:rgba(199,125,26,0.08);border:1px solid rgba(199,125,26,0.3);color:#c77d1a;">
            <div class="font-semibold mb-1">Ada kesalahan input:</div>
            <ul class="list-disc pl-5 space-y-0.5 text-xs">
                @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
            </ul>
        </div>
        @endif

        <form wire:submit="store" class="space-y-6">

            {{-- ── SECTION 1: Info Dasar ── --}}
            <div class="rounded-2xl p-6 space-y-5" style="background:#ffffff;border:1px solid #eef0f3;">
                <h2 class="text-sm font-semibold uppercase tracking-wider" style="color:#111827;">Informasi Dasar</h2>

                {{-- Nama --}}
                <div>
                    <label class="block text-sm font-medium mb-1.5" style="color:#475467;">Nama Program <span style="color:#c0453b;">*</span></label>
                    <input type="text" wire:model="name" placeholder="Nama program atau kegiatan..."
                        class="w-full px-4 py-2.5 text-sm rounded-xl outline-none transition-colors"
                        style="background:#ffffff;border:1px solid #e4e7ec;box-shadow:0 1px 2px rgba(16,24,40,0.04),0 8px 20px -8px rgba(16,24,40,0.06);color:#1d2939;"
                        onfocus="this.style.borderColor='#111827'" onblur="this.style.borderColor='#e4e7ec'">
                    @error('name')<p class="text-xs mt-1" style="color:#c0453b;">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    {{-- Jenis Program --}}
                    <div>
                        <label class="block text-sm font-medium mb-1.5" style="color:#475467;">Jenis Program <span style="color:#c0453b;">*</span></label>
                        <select wire:model.live="organization_type"
                            class="w-full px-4 py-2.5 text-sm rounded-xl outline-none"
                            style="background:#ffffff;border:1px solid #e4e7ec;box-shadow:0 1px 2px rgba(16,24,40,0.04),0 8px 20px -8px rgba(16,24,40,0.06);color:#1d2939;"
                            onfocus="this.style.borderColor='#111827'" onblur="this.style.borderColor='#e4e7ec'">
                            <option value="dkm">DKM Masjid</option>
                            <option value="perumahan">Perumahan</option>
                        </select>
                    </div>

                    {{-- Sumber Dana --}}
                    <div>
                        <label class="block text-sm font-medium mb-1.5" style="color:#475467;">Sumber Dana Awal</label>
                        <select wire:model="source_account_id"
                            class="w-full px-4 py-2.5 text-sm rounded-xl outline-none"
                            style="background:#ffffff;border:1px solid #e4e7ec;box-shadow:0 1px 2px rgba(16,24,40,0.04),0 8px 20px -8px rgba(16,24,40,0.06);color:#1d2939;"
                            onfocus="this.style.borderColor='#111827'" onblur="this.style.borderColor='#e4e7ec'">
                            <option value="">-- Tidak ada / Donasi Murni --</option>
                            @foreach($sourceAccounts as $acc)
                                <option value="{{ $acc->id }}">{{ $acc->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Status --}}
                    <div>
                        <label class="block text-sm font-medium mb-1.5" style="color:#475467;">Status <span style="color:#c0453b;">*</span></label>
                        <select wire:model="status"
                            class="w-full px-4 py-2.5 text-sm rounded-xl outline-none"
                            style="background:#ffffff;border:1px solid #e4e7ec;box-shadow:0 1px 2px rgba(16,24,40,0.04),0 8px 20px -8px rgba(16,24,40,0.06);color:#1d2939;"
                            onfocus="this.style.borderColor='#111827'" onblur="this.style.borderColor='#e4e7ec'">
                            <option value="active">Aktif</option>
                            <option value="completed">Selesai</option>
                            <option value="cancelled">Dibatalkan</option>
                        </select>
                    </div>

                    {{-- Lokasi --}}
                    <div>
                        <label class="block text-sm font-medium mb-1.5" style="color:#475467;">Lokasi</label>
                        <input type="text" wire:model="location" placeholder="Contoh: Masjid Al-Ikhlas, Blok A"
                            class="w-full px-4 py-2.5 text-sm rounded-xl outline-none"
                            style="background:#ffffff;border:1px solid #e4e7ec;box-shadow:0 1px 2px rgba(16,24,40,0.04),0 8px 20px -8px rgba(16,24,40,0.06);color:#1d2939;"
                            onfocus="this.style.borderColor='#111827'" onblur="this.style.borderColor='#e4e7ec'">
                    </div>
                </div>

                {{-- Target & Tanggal --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div>
                        <label class="block text-sm font-medium mb-1.5" style="color:#475467;">Target Donasi (Rp)</label>
                        <input type="number" wire:model="target_amount" placeholder="0" min="0"
                            class="w-full px-4 py-2.5 text-sm rounded-xl outline-none"
                            style="background:#ffffff;border:1px solid #e4e7ec;box-shadow:0 1px 2px rgba(16,24,40,0.04),0 8px 20px -8px rgba(16,24,40,0.06);color:#1d2939;"
                            onfocus="this.style.borderColor='#111827'" onblur="this.style.borderColor='#e4e7ec'">
                        @error('target_amount')<p class="text-xs mt-1" style="color:#c0453b;">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1.5" style="color:#475467;">Tanggal Mulai <span style="color:#c0453b;">*</span></label>
                        <input type="date" wire:model="start_date"
                            class="w-full px-4 py-2.5 text-sm rounded-xl outline-none"
                            style="background:#ffffff;border:1px solid #e4e7ec;box-shadow:0 1px 2px rgba(16,24,40,0.04),0 8px 20px -8px rgba(16,24,40,0.06);color:#1d2939;color-scheme:dark;"
                            onfocus="this.style.borderColor='#111827'" onblur="this.style.borderColor='#e4e7ec'">
                        @error('start_date')<p class="text-xs mt-1" style="color:#c0453b;">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1.5" style="color:#475467;">Tanggal Selesai</label>
                        <input type="date" wire:model="end_date"
                            class="w-full px-4 py-2.5 text-sm rounded-xl outline-none"
                            style="background:#ffffff;border:1px solid #e4e7ec;box-shadow:0 1px 2px rgba(16,24,40,0.04),0 8px 20px -8px rgba(16,24,40,0.06);color:#1d2939;color-scheme:dark;"
                            onfocus="this.style.borderColor='#111827'" onblur="this.style.borderColor='#e4e7ec'">
                        @error('end_date')<p class="text-xs mt-1" style="color:#c0453b;">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- URL Video --}}
                <div>
                    <label class="block text-sm font-medium mb-1.5" style="color:#475467;">URL Video (YouTube — Opsional)</label>
                    <input type="url" wire:model="videoUrl" placeholder="https://youtube.com/watch?v=..."
                        class="w-full px-4 py-2.5 text-sm rounded-xl outline-none"
                        style="background:#ffffff;border:1px solid #e4e7ec;box-shadow:0 1px 2px rgba(16,24,40,0.04),0 8px 20px -8px rgba(16,24,40,0.06);color:#1d2939;"
                        onfocus="this.style.borderColor='#111827'" onblur="this.style.borderColor='#e4e7ec'">
                    @error('videoUrl')<p class="text-xs mt-1" style="color:#c0453b;">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- ── SECTION 2: Gambar Utama ── --}}
            <div class="rounded-2xl p-6 space-y-4" style="background:#ffffff;border:1px solid #eef0f3;">
                <h2 class="text-sm font-semibold uppercase tracking-wider" style="color:#111827;">Gambar Utama</h2>

                <div class="flex flex-col sm:flex-row gap-5 items-start">
                    {{-- Preview --}}
                    <div class="shrink-0">
                        @if($image && !$errors->has('image'))
                            <img src="{{ $image->temporaryUrl() }}" class="w-32 h-24 object-cover rounded-xl" style="border:1px solid #e4e7ec;">
                        @elseif($existingImage)
                            <div class="relative group">
                                <img src="{{ Storage::url($existingImage) }}" class="w-32 h-24 object-cover rounded-xl" style="border:1px solid #e4e7ec;">
                                <button type="button" wire:click="removeImage"
                                    class="absolute -top-2 -right-2 w-6 h-6 rounded-full text-xs flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity"
                                    style="background:#c0453b;color:#1d2939;">✕</button>
                            </div>
                        @else
                            <div class="w-32 h-24 rounded-xl flex items-center justify-center" style="background:#ffffff;border:1px dashed #e4e7ec;">
                                <svg class="w-8 h-8 opacity-20" fill="none" viewBox="0 0 24 24" stroke="#111827"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14"/></svg>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1">
                        <input type="file" wire:model="image" accept="image/jpeg,image/png,image/webp"
                            class="block w-full text-sm" style="color:#7c8698;">
                        <div wire:loading wire:target="image" class="text-xs mt-1" style="color:#111827;">Mengunggah...</div>
                        @error('image')<p class="text-xs mt-1" style="color:#c0453b;">{{ $message }}</p>@enderror
                        <p class="text-xs mt-2" style="color:#98a2b3;">JPG, PNG, WebP — maks. 2 MB. Rasio 16:9 direkomendasikan.</p>
                    </div>
                </div>
            </div>

            {{-- ── SECTION 3: Deskripsi Singkat ── --}}
            <div class="rounded-2xl p-6 space-y-3" style="background:#ffffff;border:1px solid #eef0f3;">
                <h2 class="text-sm font-semibold uppercase tracking-wider" style="color:#111827;">Deskripsi Singkat</h2>
                <p class="text-xs" style="color:#98a2b3;">Ringkasan 1–2 kalimat yang tampil di kartu program (teks biasa).</p>
                <textarea wire:model="description" rows="3" placeholder="Ringkasan singkat program untuk ditampilkan di listing..."
                    class="w-full px-4 py-3 text-sm rounded-xl outline-none resize-y"
                    style="background:#ffffff;border:1px solid #e4e7ec;box-shadow:0 1px 2px rgba(16,24,40,0.04),0 8px 20px -8px rgba(16,24,40,0.06);color:#1d2939;min-height:80px;"
                    onfocus="this.style.borderColor='#111827'" onblur="this.style.borderColor='#e4e7ec'"></textarea>
            </div>

            {{-- ── SECTION 4: Konten Lengkap (CKEditor) ── --}}
            <div class="rounded-2xl p-6 space-y-3" style="background:#ffffff;border:1px solid #eef0f3;">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-semibold uppercase tracking-wider" style="color:#111827;">Konten Lengkap</h2>
                        <p class="text-xs mt-0.5" style="color:#98a2b3;">Opsional — deskripsi panjang seperti artikel/blog dengan format teks lengkap.</p>
                    </div>
                    <span class="px-2.5 py-1 rounded-lg text-xs font-medium" style="background:rgba(16,24,40,0.1);color:#111827;border:1px solid rgba(16,24,40,0.2);">Opsional</span>
                </div>

                {{-- CKEditor container: wire:ignore prevents Livewire from touching the DOM inside --}}
                <div wire:ignore id="ck-editor-wrapper">
                    <div style="border:1px solid #e4e7ec;border-radius:0.75rem;overflow:hidden;min-height:300px;">
                        {{-- CKEditor mounts on this div. data-initial-content carries existing HTML. --}}
                        <div id="campaignContent"
                             data-initial-content="{{ htmlspecialchars($content ?? '') }}"
                             style="min-height:300px;"></div>
                    </div>
                </div>
                @error('content')<p class="text-xs mt-1" style="color:#c0453b;">{{ $message }}</p>@enderror
            </div>

            {{-- ── SECTION 5: Galeri Foto (edit mode only) ── --}}
            @if($campaignId && $existingPhotos->count() > 0)
            <div class="rounded-2xl p-6 space-y-4" style="background:#ffffff;border:1px solid #eef0f3;">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-semibold uppercase tracking-wider" style="color:#111827;">Galeri Foto ({{ $existingPhotos->count() }})</h2>
                    <p class="text-xs" style="color:#98a2b3;">Foto baru dapat ditambah dari halaman Detail Program.</p>
                </div>
                <div class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 gap-2">
                    @foreach($existingPhotos as $photo)
                    <div class="relative group rounded-lg overflow-hidden" style="aspect-ratio:1;" wire:key="gphoto-{{ $photo->id }}">
                        <img src="{{ Storage::url($photo->photo_path) }}" class="w-full h-full object-cover">
                        <button type="button" wire:click="deleteGalleryPhoto({{ $photo->id }})" wire:confirm="Hapus foto ini?"
                            class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity text-sm font-bold"
                            style="background:rgba(192,69,59,0.8);color:#1d2939;">✕</button>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- ── ACTION BUTTONS ── --}}
            <div class="flex flex-col sm:flex-row justify-between gap-3 pt-2 pb-8">
                <button type="button" wire:click="cancel"
                    class="px-5 py-2.5 text-sm rounded-xl font-medium transition-colors"
                    style="background:#ffffff;color:#344054;border:1px solid #e4e7ec;"
                    onmouseover="this.style.background='#ffffff'" onmouseout="this.style.background='#ffffff'">
                    ← Batal
                </button>
                <button type="submit"
                    class="inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl text-sm font-semibold transition-colors"
                    style="background:#111827;color:#ffffff;"
                    onmouseover="this.style.background='#1f2a37'" onmouseout="this.style.background='#1f2a37'"
                    wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="store">
                        <svg class="w-4 h-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        {{ $campaignId ? 'Simpan Perubahan' : 'Buat Program' }}
                    </span>
                    <span wire:loading wire:target="store" class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                        Menyimpan...
                    </span>
                </button>
            </div>

        </form>
    </div>

    {{-- CKEditor dark theme CSS --}}
    @push('styles')
    <style>
        .ck.ck-editor__main>.ck-editor__editable { background:#ffffff !important; color:#1d2939 !important; min-height:280px; border:none !important; border-top:1px solid #e4e7ec !important; }
        .ck.ck-toolbar { background:#ffffff !important; border-color:#e4e7ec !important; }
        .ck.ck-toolbar .ck-toolbar__separator { background:#e4e7ec !important; }
        .ck.ck-button { color:#344054 !important; }
        .ck.ck-button:hover,.ck.ck-button.ck-on { background:#e4e7ec !important; color:#111827 !important; }
        .ck.ck-editor__editable.ck-focused { border:none !important; box-shadow:none !important; }
        .ck-content h2,.ck-content h3 { color:#111827; }
        .ck-content a { color:#111827; }
        .ck.ck-list { background:#ffffff !important; border-color:#e4e7ec !important; }
        .ck.ck-list__item .ck-button:hover { background:#ffffff !important; }
        .ck.ck-balloon-panel { background:#ffffff !important; border-color:#e4e7ec !important; }
        .ck.ck-dropdown__panel { background:#ffffff !important; border-color:#e4e7ec !important; }
        .ck.ck-input-text { background:#ffffff !important; border-color:#e4e7ec !important; color:#1d2939 !important; }
    </style>
    @endpush

    @push('scripts')
    <script data-navigate-once>
    (function () {
        function initCKEditor() {
            var el = document.getElementById('campaignContent');
            if (!el) return;
            if (el.__ckBound) return;
            if (typeof ClassicEditor === 'undefined') {
                // CKEditor not yet loaded — retry shortly
                setTimeout(initCKEditor, 100);
                return;
            }
            el.__ckBound = true;

            ClassicEditor.create(el, {
                toolbar: [
                    'heading','|','bold','italic','underline','strikethrough','|',
                    'bulletedList','numberedList','|','blockQuote','link','|',
                    'insertTable','|','undo','redo'
                ],
            }).then(function (editor) {
                el.__ckInstance = editor;
                var initial = el.dataset.initialContent || '';
                if (initial) editor.setData(initial);
                editor.model.document.on('change:data', function () {
                    @this.set('content', editor.getData(), false);
                });
            }).catch(function (err) {
                console.error('[CKEditor init]', err);
            });
        }

        function destroyCKEditor() {
            var el = document.getElementById('campaignContent');
            if (el && el.__ckInstance) {
                el.__ckInstance.destroy().catch(function(){});
                el.__ckInstance = null;
                el.__ckBound = false;
            }
        }

        document.addEventListener('livewire:navigated', initCKEditor);
        document.addEventListener('livewire:navigating', destroyCKEditor);

        // First load (head script means CKEditor is already available)
        initCKEditor();
    })();
    </script>
    @endpush
</div>
