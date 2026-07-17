<div class="min-h-screen" style="background-color:#f7f7f7;">
    <div class="max-w-2xl mx-auto px-4 py-8">

        {{-- Header --}}
        <div class="flex items-center gap-3 mb-8">
            <a href="{{ route('penghuni.keluarga') }}" wire:navigate
               class="p-2 rounded-lg transition-colors"
               style="background-color:#f7f7f7; color:#161e2d;">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-xl font-bold" style="color:#161e2d;">
                    {{ $memberId ? 'Edit Anggota Keluarga' : 'Tambah Anggota Keluarga' }}
                </h1>
                <p class="text-sm" style="color:#a3abb0;">Lengkapi data anggota keluarga Anda</p>
            </div>
        </div>

        @if (session('success'))
            <div class="mb-6 p-4 rounded-xl text-sm font-medium" style="background-color:#e3f1ea; color:#12805c; border:1px solid #0e6d4f;">
                {{ session('success') }}
            </div>
        @endif

        <form wire:submit="save" class="space-y-6">

            {{-- Foto --}}
            <div class="rounded-2xl p-6" style="background-color:#f7f7f7; border:1px solid #f7f7f7;">
                <h2 class="text-base font-semibold mb-4" style="color:#161e2d;">Foto Anggota</h2>

                <div class="flex flex-col items-center gap-4">
                    {{-- Preview --}}
                    @if ($photo)
                        <img src="{{ $photo->temporaryUrl() }}" alt="Preview"
                             class="w-32 h-32 rounded-full object-cover"
                             style="border:3px solid #1563df;">
                    @elseif ($existingPhoto)
                        <img src="{{ Storage::disk('public')->url($existingPhoto) }}" alt="Foto"
                             class="w-32 h-32 rounded-full object-cover"
                             style="border:3px solid #1563df;">
                    @else
                        <div class="w-32 h-32 rounded-full flex items-center justify-center text-4xl font-bold"
                             style="background-color:#f7f7f7; color:#161e2d; border:2px dashed #d9d9d9;">
                            {{ $name ? strtoupper(substr($name, 0, 1)) : '?' }}
                        </div>
                    @endif

                    <div class="flex gap-3 flex-wrap justify-center">
                        <label class="cursor-pointer px-4 py-2 rounded-xl text-sm font-medium transition-colors"
                               style="background-color:#161e2d; color:#161e2d; border:1px solid #1563df;">
                            <span wire:loading.remove wire:target="photo">
                                {{ $existingPhoto || $photo ? 'Ganti Foto' : 'Pilih Foto' }}
                            </span>
                            <span wire:loading wire:target="photo">Mengupload...</span>
                            <input type="file" class="hidden" wire:model="photo" accept="image/*">
                        </label>

                        @if ($existingPhoto || $photo)
                            <button type="button" wire:click="removePhoto"
                                    class="px-4 py-2 rounded-xl text-sm font-medium transition-colors"
                                    style="background-color:#f7e7e4; color:#c0453b; border:1px solid #a23a30;">
                                Hapus Foto
                            </button>
                        @endif
                    </div>

                    @error('photo')
                        <p class="text-sm" style="color:#c0453b;">{{ $message }}</p>
                    @enderror

                    <p class="text-xs" style="color:#a3abb0;">Format: JPG, PNG, WEBP. Maks 2MB.</p>
                </div>
            </div>

            {{-- Data Diri --}}
            <div class="rounded-2xl p-6 space-y-5" style="background-color:#f7f7f7; border:1px solid #f7f7f7;">
                <h2 class="text-base font-semibold" style="color:#161e2d;">Data Diri</h2>

                {{-- Nama --}}
                <div>
                    <label class="block text-sm font-medium mb-1.5" style="color:#5c6368;">Nama Lengkap <span style="color:#161e2d;">*</span></label>
                    <input type="text" wire:model="name" placeholder="Nama lengkap"
                           class="w-full px-4 py-3 rounded-xl text-sm outline-none transition-colors"
                           style="background-color:#f7f7f7; color:#161e2d; border:1px solid #f7f7f7;">
                    @error('name') <p class="mt-1 text-xs" style="color:#c0453b;">{{ $message }}</p> @enderror
                </div>

                {{-- Hubungan & Jenis Kelamin --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1.5" style="color:#5c6368;">Hubungan <span style="color:#161e2d;">*</span></label>
                        <select wire:model="relationship"
                                class="w-full px-4 py-3 rounded-xl text-sm outline-none"
                                style="background-color:#f7f7f7; color:#161e2d; border:1px solid #f7f7f7;">
                            <option value="istri">Istri</option>
                            <option value="suami">Suami</option>
                            <option value="anak">Anak</option>
                            <option value="orang_tua">Orang Tua</option>
                            <option value="mertua">Mertua</option>
                            <option value="saudara">Saudara</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                        @error('relationship') <p class="mt-1 text-xs" style="color:#c0453b;">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1.5" style="color:#5c6368;">Jenis Kelamin <span style="color:#161e2d;">*</span></label>
                        <select wire:model="gender"
                                class="w-full px-4 py-3 rounded-xl text-sm outline-none"
                                style="background-color:#f7f7f7; color:#161e2d; border:1px solid #f7f7f7;">
                            <option value="laki-laki">Laki-laki</option>
                            <option value="perempuan">Perempuan</option>
                        </select>
                        @error('gender') <p class="mt-1 text-xs" style="color:#c0453b;">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- NIK --}}
                <div>
                    <label class="block text-sm font-medium mb-1.5" style="color:#5c6368;">NIK (Opsional)</label>
                    <input type="text" wire:model="nik" placeholder="Nomor Induk Kependudukan"
                           maxlength="20"
                           class="w-full px-4 py-3 rounded-xl text-sm outline-none"
                           style="background-color:#f7f7f7; color:#161e2d; border:1px solid #f7f7f7;">
                    @error('nik') <p class="mt-1 text-xs" style="color:#c0453b;">{{ $message }}</p> @enderror
                </div>

                {{-- Tanggal Lahir --}}
                <div>
                    <label class="block text-sm font-medium mb-1.5" style="color:#5c6368;">Tanggal Lahir (Opsional)</label>
                    <input type="date" wire:model="birth_date"
                           class="w-full px-4 py-3 rounded-xl text-sm outline-none"
                           style="background-color:#f7f7f7; color:#161e2d; border:1px solid #f7f7f7;">
                    @error('birth_date') <p class="mt-1 text-xs" style="color:#c0453b;">{{ $message }}</p> @enderror
                </div>

                {{-- Catatan --}}
                <div>
                    <label class="block text-sm font-medium mb-1.5" style="color:#5c6368;">Catatan (Opsional)</label>
                    <textarea wire:model="memberNotes" rows="3"
                              placeholder="Informasi tambahan..."
                              class="w-full px-4 py-3 rounded-xl text-sm outline-none resize-none"
                              style="background-color:#f7f7f7; color:#161e2d; border:1px solid #f7f7f7;"></textarea>
                    @error('memberNotes') <p class="mt-1 text-xs" style="color:#c0453b;">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Tombol --}}
            <div class="flex gap-3">
                <a href="{{ route('penghuni.keluarga') }}" wire:navigate
                   class="flex-1 py-3 rounded-xl text-sm font-semibold text-center transition-colors"
                   style="background-color:#f7f7f7; color:#5c6368; border:1px solid #f7f7f7;">
                    Batal
                </a>
                <button type="submit"
                        wire:loading.attr="disabled"
                        class="flex-1 py-3 rounded-xl text-sm font-semibold transition-colors"
                        style="background-color:#161e2d; color:#ffffff;">
                    <span wire:loading.remove>Simpan Data</span>
                    <span wire:loading>Menyimpan...</span>
                </button>
            </div>

        </form>
    </div>
</div>
