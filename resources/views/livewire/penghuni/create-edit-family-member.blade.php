<div class="min-h-screen" style="background-color:#F1F3EC;">
    <div class="max-w-2xl mx-auto px-4 py-8">

        {{-- Header --}}
        <div class="flex items-center gap-3 mb-8">
            <a href="{{ route('penghuni.keluarga') }}" wire:navigate
               class="p-2 rounded-lg transition-colors"
               style="background-color:#F1F3EC; color:#17231E;">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-xl font-bold" style="color:#17231E;">
                    {{ $memberId ? 'Edit Anggota Keluarga' : 'Tambah Anggota Keluarga' }}
                </h1>
                <p class="text-sm" style="color:#909A8F;">Lengkapi data anggota keluarga Anda</p>
            </div>
        </div>

        @if (session('success'))
            <div class="mb-6 p-4 rounded-xl text-sm font-medium" style="background-color:#e3f1ea; color:#12805c; border:1px solid #0e6d4f;">
                {{ session('success') }}
            </div>
        @endif

        <form wire:submit="save" class="space-y-6">

            {{-- Foto --}}
            <div class="rounded-2xl p-6" style="background-color:#F1F3EC; border:1px solid #F1F3EC;">
                <h2 class="text-base font-semibold mb-4" style="color:#17231E;">Foto Anggota</h2>

                <div class="flex flex-col items-center gap-4">
                    {{-- Preview --}}
                    @if ($photo)
                        <img src="{{ $photo->temporaryUrl() }}" alt="Preview"
                             class="w-32 h-32 rounded-full object-cover"
                             style="border:3px solid #164A40;">
                    @elseif ($existingPhoto)
                        <img src="{{ Storage::disk('public')->url($existingPhoto) }}" alt="Foto"
                             class="w-32 h-32 rounded-full object-cover"
                             style="border:3px solid #164A40;">
                    @else
                        <div class="w-32 h-32 rounded-full flex items-center justify-center text-4xl font-bold"
                             style="background-color:#F1F3EC; color:#17231E; border:2px dashed #D8D6C9;">
                            {{ $name ? strtoupper(substr($name, 0, 1)) : '?' }}
                        </div>
                    @endif

                    <div class="flex gap-3 flex-wrap justify-center">
                        <label class="cursor-pointer px-4 py-2 rounded-xl text-sm font-medium transition-colors"
                               style="background-color:#17231E; color:#17231E; border:1px solid #164A40;">
                            <span wire:loading.remove wire:target="photo">
                                {{ $existingPhoto || $photo ? 'Ganti Foto' : 'Pilih Foto' }}
                            </span>
                            <span wire:loading wire:target="photo">Mengupload...</span>
                            <input type="file" class="hidden" wire:model="photo" accept="image/*">
                        </label>

                        @if ($existingPhoto || $photo)
                            <button type="button" wire:click="removePhoto"
                                    class="px-4 py-2 rounded-xl text-sm font-medium transition-colors"
                                    style="background-color:#f7e7e4; color:#B0402C; border:1px solid #a23a30;">
                                Hapus Foto
                            </button>
                        @endif
                    </div>

                    @error('photo')
                        <p class="text-sm" style="color:#B0402C;">{{ $message }}</p>
                    @enderror

                    <p class="text-xs" style="color:#909A8F;">Format: JPG, PNG, WEBP. Maks 2MB.</p>
                </div>
            </div>

            {{-- Data Diri --}}
            <div class="rounded-2xl p-6 space-y-5" style="background-color:#F1F3EC; border:1px solid #F1F3EC;">
                <h2 class="text-base font-semibold" style="color:#17231E;">Data Diri</h2>

                {{-- Nama --}}
                <div>
                    <label class="block text-sm font-medium mb-1.5" style="color:#586359;">Nama Lengkap <span style="color:#17231E;">*</span></label>
                    <input type="text" wire:model="name" placeholder="Nama lengkap"
                           class="w-full px-4 py-3 rounded-xl text-sm outline-none transition-colors"
                           style="background-color:#F1F3EC; color:#17231E; border:1px solid #F1F3EC;">
                    @error('name') <p class="mt-1 text-xs" style="color:#B0402C;">{{ $message }}</p> @enderror
                </div>

                {{-- Hubungan & Jenis Kelamin --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1.5" style="color:#586359;">Hubungan <span style="color:#17231E;">*</span></label>
                        <select wire:model="relationship"
                                class="w-full px-4 py-3 rounded-xl text-sm outline-none"
                                style="background-color:#F1F3EC; color:#17231E; border:1px solid #F1F3EC;">
                            <option value="istri">Istri</option>
                            <option value="suami">Suami</option>
                            <option value="anak">Anak</option>
                            <option value="orang_tua">Orang Tua</option>
                            <option value="mertua">Mertua</option>
                            <option value="saudara">Saudara</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                        @error('relationship') <p class="mt-1 text-xs" style="color:#B0402C;">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1.5" style="color:#586359;">Jenis Kelamin <span style="color:#17231E;">*</span></label>
                        <select wire:model="gender"
                                class="w-full px-4 py-3 rounded-xl text-sm outline-none"
                                style="background-color:#F1F3EC; color:#17231E; border:1px solid #F1F3EC;">
                            <option value="laki-laki">Laki-laki</option>
                            <option value="perempuan">Perempuan</option>
                        </select>
                        @error('gender') <p class="mt-1 text-xs" style="color:#B0402C;">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- NIK --}}
                <div>
                    <label class="block text-sm font-medium mb-1.5" style="color:#586359;">NIK (Opsional)</label>
                    <input type="text" wire:model="nik" placeholder="Nomor Induk Kependudukan"
                           maxlength="20"
                           class="w-full px-4 py-3 rounded-xl text-sm outline-none"
                           style="background-color:#F1F3EC; color:#17231E; border:1px solid #F1F3EC;">
                    @error('nik') <p class="mt-1 text-xs" style="color:#B0402C;">{{ $message }}</p> @enderror
                </div>

                {{-- Tanggal Lahir --}}
                <div>
                    <label class="block text-sm font-medium mb-1.5" style="color:#586359;">Tanggal Lahir (Opsional)</label>
                    <input type="date" wire:model="birth_date"
                           class="w-full px-4 py-3 rounded-xl text-sm outline-none"
                           style="background-color:#F1F3EC; color:#17231E; border:1px solid #F1F3EC;">
                    @error('birth_date') <p class="mt-1 text-xs" style="color:#B0402C;">{{ $message }}</p> @enderror
                </div>

                {{-- Catatan --}}
                <div>
                    <label class="block text-sm font-medium mb-1.5" style="color:#586359;">Catatan (Opsional)</label>
                    <textarea wire:model="memberNotes" rows="3"
                              placeholder="Informasi tambahan..."
                              class="w-full px-4 py-3 rounded-xl text-sm outline-none resize-none"
                              style="background-color:#F1F3EC; color:#17231E; border:1px solid #F1F3EC;"></textarea>
                    @error('memberNotes') <p class="mt-1 text-xs" style="color:#B0402C;">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Tombol --}}
            <div class="flex gap-3">
                <a href="{{ route('penghuni.keluarga') }}" wire:navigate
                   class="flex-1 py-3 rounded-xl text-sm font-semibold text-center transition-colors"
                   style="background-color:#F1F3EC; color:#586359; border:1px solid #F1F3EC;">
                    Batal
                </a>
                <button type="submit"
                        wire:loading.attr="disabled"
                        class="flex-1 py-3 rounded-xl text-sm font-semibold transition-colors"
                        style="background-color:#17231E; color:#ffffff;">
                    <span wire:loading.remove>Simpan Data</span>
                    <span wire:loading>Menyimpan...</span>
                </button>
            </div>

        </form>
    </div>
</div>
