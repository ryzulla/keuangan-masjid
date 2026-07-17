<div>
    <x-slot name="header">
        <h2 class="font-semibold text-base" style="color:#161e2d;">
            {{ $resident && $resident->exists ? 'Edit Penghuni: ' . $resident->name : 'Tambah Penghuni Baru' }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

        @if(session('error'))
            <div class="mb-5 rounded-xl p-4 text-sm flex items-center gap-2" style="background:rgba(192,69,59,0.1);border:1px solid rgba(192,69,59,0.3);color:#c0453b;">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- Page Header --}}
        <div class="rounded-2xl p-5 mb-6" style="background:linear-gradient(135deg,#ffffff 0%,#ffffff 62%);border:1px solid rgba(21,99,223,0.35);">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-lg" style="color:#161e2d;font-family:'Manrope',serif;">
                        {{ $resident && $resident->exists ? 'Edit Data Penghuni' : 'Data Penghuni Baru' }}
                    </h3>
                    <p class="text-sm mt-1" style="color:#161e2d;">Lengkapi informasi pribadi, kepemilikan rumah, dan anggota keluarga</p>
                </div>
                <button wire:click="cancel" type="button"
                    class="inline-flex items-center gap-1.5 text-sm px-3 py-1.5 rounded-xl transition-colors"
                    style="background:#f7f7f7;color:#5c6368;border:1px solid #d9d9d9;"
                    onmouseover="this.style.background='#f7f7f7'" onmouseout="this.style.background='#f7f7f7'">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Kembali
                </button>
            </div>
        </div>

        <div class="space-y-6">

            {{-- ── Informasi Pribadi ── --}}
            <div class="rounded-2xl p-6" style="background:#ffffff;border:1px solid #e4e4e4;box-shadow:0 1px 2px rgba(21,99,223,0.04),0 8px 20px -8px rgba(21,99,223,0.06);">
                <h4 class="text-xs font-semibold uppercase tracking-wider mb-4" style="color:#161e2d;">Informasi Pribadi</h4>

                {{-- Foto Penghuni --}}
                <div class="flex items-start gap-5 mb-5 pb-5" style="border-bottom:1px solid #f7f7f7;">
                    {{-- Preview --}}
                    <div class="shrink-0">
                        @if($photo)
                            <img src="{{ $photo->temporaryUrl() }}" alt="Preview"
                                class="w-24 h-24 rounded-2xl object-cover"
                                style="border:2px solid rgba(21,99,223,0.4);">
                        @elseif($existingPhoto)
                            <img src="{{ Storage::disk('public')->url($existingPhoto) }}" alt="Foto Penghuni"
                                class="w-24 h-24 rounded-2xl object-cover"
                                style="border:2px solid rgba(21,99,223,0.4);">
                        @else
                            <div class="w-24 h-24 rounded-2xl flex items-center justify-center text-3xl font-bold"
                                style="background:rgba(21,99,223,0.1);color:#161e2d;border:2px dashed rgba(21,99,223,0.25);">
                                {{ $name ? strtoupper(substr($name, 0, 1)) : '?' }}
                            </div>
                        @endif
                    </div>
                    {{-- Upload Controls --}}
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium mb-1" style="color:#5c6368;">Foto Penghuni</p>
                        <p class="text-xs mb-3" style="color:#a3abb0;">Format JPG/PNG/WEBP, maksimal 2 MB</p>
                        <div class="flex items-center gap-2 flex-wrap">
                            <label class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium cursor-pointer transition-colors"
                                style="background:rgba(21,99,223,0.1);color:#161e2d;border:1px solid rgba(21,99,223,0.2);"
                                onmouseover="this.style.background='rgba(21,99,223,0.2)'" onmouseout="this.style.background='rgba(21,99,223,0.1)'">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                {{ ($photo || $existingPhoto) ? 'Ganti Foto' : 'Pilih Foto' }}
                                <input type="file" wire:model="photo" accept="image/*" class="hidden">
                            </label>
                            @if($photo || $existingPhoto)
                                <button type="button" wire:click="removePhoto"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium transition-colors"
                                    style="background:rgba(192,69,59,0.08);color:#c0453b;border:1px solid rgba(192,69,59,0.2);"
                                    onmouseover="this.style.background='rgba(192,69,59,0.15)'" onmouseout="this.style.background='rgba(192,69,59,0.08)'">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Hapus Foto
                                </button>
                            @endif
                        </div>
                        <div wire:loading wire:target="photo" class="mt-2 text-xs" style="color:#161e2d;">
                            Mengunggah...
                        </div>
                        @error('photo') <p class="text-xs mt-2" style="color:#c0453b;">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium mb-1.5" style="color:#5c6368;">Nama Lengkap <span style="color:#c0453b;">*</span></label>
                        <input wire:model="name" type="text" placeholder="Nama lengkap kepala keluarga"
                            style="background:#ffffff;border:1px solid #e4e4e4;color:#161e2d;border-radius:0.75rem;padding:0.625rem 0.875rem;width:100%;font-size:0.875rem;outline:none;"
                            onfocus="this.style.borderColor='#1563df'" onblur="this.style.borderColor='#e4e4e4'">
                        @error('name') <p class="text-xs mt-1" style="color:#c0453b;">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1.5" style="color:#5c6368;">NIK <span class="text-xs font-normal" style="color:#a3abb0;">(terenkripsi)</span></label>
                        <input wire:model="nik" type="text" placeholder="16 digit NIK" maxlength="20"
                            style="background:#ffffff;border:1px solid #e4e4e4;color:#161e2d;border-radius:0.75rem;padding:0.625rem 0.875rem;width:100%;font-size:0.875rem;outline:none;"
                            onfocus="this.style.borderColor='#1563df'" onblur="this.style.borderColor='#e4e4e4'">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1.5" style="color:#5c6368;">No. Telepon</label>
                        <input wire:model="phone" type="tel" placeholder="08xx-xxxx-xxxx"
                            style="background:#ffffff;border:1px solid #e4e4e4;color:#161e2d;border-radius:0.75rem;padding:0.625rem 0.875rem;width:100%;font-size:0.875rem;outline:none;"
                            onfocus="this.style.borderColor='#1563df'" onblur="this.style.borderColor='#e4e4e4'">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1.5" style="color:#5c6368;">No. WhatsApp</label>
                        <input wire:model="whatsapp" type="tel" placeholder="08xx-xxxx-xxxx"
                            style="background:#ffffff;border:1px solid #e4e4e4;color:#161e2d;border-radius:0.75rem;padding:0.625rem 0.875rem;width:100%;font-size:0.875rem;outline:none;"
                            onfocus="this.style.borderColor='#1563df'" onblur="this.style.borderColor='#e4e4e4'">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1.5" style="color:#5c6368;">Email</label>
                        <input wire:model="email" type="email" placeholder="email@contoh.com"
                            style="background:#ffffff;border:1px solid #e4e4e4;color:#161e2d;border-radius:0.75rem;padding:0.625rem 0.875rem;width:100%;font-size:0.875rem;outline:none;"
                            onfocus="this.style.borderColor='#1563df'" onblur="this.style.borderColor='#e4e4e4'">
                        @error('email') <p class="text-xs mt-1" style="color:#c0453b;">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium mb-1.5" style="color:#5c6368;">Catatan</label>
                        <textarea wire:model="notes" rows="2" placeholder="Catatan tambahan (opsional)"
                            style="background:#ffffff;border:1px solid #e4e4e4;color:#161e2d;border-radius:0.75rem;padding:0.625rem 0.875rem;width:100%;font-size:0.875rem;outline:none;resize:none;"
                            onfocus="this.style.borderColor='#1563df'" onblur="this.style.borderColor='#e4e4e4'"></textarea>
                    </div>

                    <div>
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" wire:model="is_active" style="accent-color:#161e2d;" class="rounded w-4 h-4">
                            <span class="text-sm" style="color:#5c6368;">Penghuni Aktif</span>
                        </label>
                    </div>
                </div>
            </div>

            {{-- ── Kepemilikan Rumah ── --}}
            <div class="rounded-2xl p-6" style="background:#ffffff;border:1px solid #e4e4e4;box-shadow:0 1px 2px rgba(21,99,223,0.04),0 8px 20px -8px rgba(21,99,223,0.06);">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h4 class="text-xs font-semibold uppercase tracking-wider" style="color:#161e2d;">Kepemilikan / Hunian Rumah</h4>
                        <p class="text-xs mt-0.5" style="color:#a3abb0;">Setiap rumah wajib punya pemilik. Jika dikontrakkan, tambahkan sebagai Kontrak / Kos.</p>
                    </div>
                    <button type="button" wire:click="addHouseAssignment"
                        class="inline-flex items-center gap-1 text-xs font-medium px-2.5 py-1.5 rounded-lg transition-colors shrink-0"
                        style="background:rgba(21,99,223,0.1);color:#161e2d;border:1px solid rgba(21,99,223,0.2);"
                        onmouseover="this.style.background='rgba(21,99,223,0.2)'" onmouseout="this.style.background='rgba(21,99,223,0.1)'">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Tambah Rumah
                    </button>
                </div>

                @if(empty($houseAssignments))
                    <p class="text-xs italic text-center py-5 rounded-xl" style="color:#a3abb0;border:1px dashed #e4e4e4;">
                        Klik "Tambah Rumah" untuk menetapkan blok rumah.
                    </p>
                @endif

                <div class="space-y-4">
                    @foreach($houseAssignments as $i => $assignment)
                    <div class="rounded-xl p-4" style="border:1px solid #e4e4e4;background:#ffffff;" wire:key="ha-{{ $i }}">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs font-semibold" style="color:#161e2d;">Rumah #{{ $i + 1 }}</span>
                            <div class="flex items-center gap-3">
                                <label class="flex items-center gap-1.5 text-xs cursor-pointer" style="color:#a3abb0;">
                                    <input type="checkbox" wire:model="houseAssignments.{{ $i }}.is_primary_residence" style="accent-color:#161e2d;" class="rounded">
                                    ★ Domisili Utama
                                </label>
                                <label class="flex items-center gap-1.5 text-xs cursor-pointer" style="color:#a3abb0;" title="Yang menanggung/membayar IPL untuk unit ini (mis. penyewa)">
                                    <input type="checkbox" wire:model="houseAssignments.{{ $i }}.is_ipl_payer" style="accent-color:#161e2d;" class="rounded">
                                    Penanggung IPL
                                </label>
                                <button type="button" wire:click="removeHouseAssignment({{ $i }})" style="color:#c0453b;"
                                    onmouseover="this.style.color='#c0453b'" onmouseout="this.style.color='#c0453b'">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mb-3">
                            <div>
                                <label class="block text-xs mb-1" style="color:#a3abb0;">Blok Rumah</label>
                                <select wire:model="houseAssignments.{{ $i }}.house_block_id"
                                    style="background:#ffffff;border:1px solid #e4e4e4;color:#161e2d;border-radius:0.5rem;padding:0.375rem 0.5rem;width:100%;font-size:0.8rem;outline:none;"
                                    onfocus="this.style.borderColor='#1563df'" onblur="this.style.borderColor='#e4e4e4'">
                                    <option value="">-- Pilih --</option>
                                    @foreach($houseBlocks as $blk)
                                        <option value="{{ $blk->id }}">{{ $blk->block_code }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs mb-1" style="color:#a3abb0;">Status Kepemilikan</label>
                                <select wire:model.live="houseAssignments.{{ $i }}.ownership_type"
                                    style="background:#ffffff;border:1px solid #e4e4e4;color:#161e2d;border-radius:0.5rem;padding:0.375rem 0.5rem;width:100%;font-size:0.8rem;outline:none;"
                                    onfocus="this.style.borderColor='#1563df'" onblur="this.style.borderColor='#e4e4e4'">
                                    <option value="pemilik">Pemilik</option>
                                    <option value="kontrak">Kontrak / Sewa</option>
                                    <option value="kos">Kos</option>
                                </select>
                                @error("houseAssignments.{$i}.ownership_type") <p class="text-xs mt-1" style="color:#c0453b;">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-xs mb-1" style="color:#a3abb0;">Status Hunian</label>
                                <select wire:model="houseAssignments.{{ $i }}.occupancy_status"
                                    style="background:#ffffff;border:1px solid #e4e4e4;color:#161e2d;border-radius:0.5rem;padding:0.375rem 0.5rem;width:100%;font-size:0.8rem;outline:none;"
                                    onfocus="this.style.borderColor='#1563df'" onblur="this.style.borderColor='#e4e4e4'">
                                    <option value="dihuni">Dihuni</option>
                                    <option value="kosong">Kosong</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs mb-1" style="color:#a3abb0;">Menghuni Sejak</label>
                                <input wire:model="houseAssignments.{{ $i }}.resident_since" type="date"
                                    style="background:#ffffff;border:1px solid #e4e4e4;color:#161e2d;border-radius:0.5rem;padding:0.375rem 0.5rem;width:100%;font-size:0.8rem;outline:none;"
                                    onfocus="this.style.borderColor='#1563df'" onblur="this.style.borderColor='#e4e4e4'">
                            </div>

                            {{-- Contract fields: only show for kontrak/kos --}}
                            @if(in_array($assignment['ownership_type'] ?? 'pemilik', ['kontrak', 'kos']))
                            <div>
                                <label class="block text-xs mb-1" style="color:#c77d1a;">Mulai Kontrak</label>
                                <input wire:model="houseAssignments.{{ $i }}.contract_start_date" type="date"
                                    style="background:#ffffff;border:1px solid rgba(199,125,26,0.3);color:#161e2d;border-radius:0.5rem;padding:0.375rem 0.5rem;width:100%;font-size:0.8rem;outline:none;"
                                    onfocus="this.style.borderColor='#c77d1a'" onblur="this.style.borderColor='rgba(199,125,26,0.3)'">
                            </div>
                            <div>
                                <label class="block text-xs mb-1" style="color:#c77d1a;">Akhir Kontrak</label>
                                <input wire:model="houseAssignments.{{ $i }}.contract_end_date" type="date"
                                    style="background:#ffffff;border:1px solid rgba(199,125,26,0.3);color:#161e2d;border-radius:0.5rem;padding:0.375rem 0.5rem;width:100%;font-size:0.8rem;outline:none;"
                                    onfocus="this.style.borderColor='#c77d1a'" onblur="this.style.borderColor='rgba(199,125,26,0.3)'">
                            </div>
                            <div class="col-span-2 sm:col-span-1">
                                <label class="block text-xs mb-1" style="color:#c77d1a;">Sewa / Bulan (Rp)</label>
                                <input wire:model="houseAssignments.{{ $i }}.monthly_rent" type="number" min="0" placeholder="0"
                                    style="background:#ffffff;border:1px solid rgba(199,125,26,0.3);color:#161e2d;border-radius:0.5rem;padding:0.375rem 0.5rem;width:100%;font-size:0.8rem;outline:none;"
                                    onfocus="this.style.borderColor='#c77d1a'" onblur="this.style.borderColor='rgba(199,125,26,0.3)'">
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- ── Anggota Keluarga ── --}}
            <div class="rounded-2xl p-6" style="background:#ffffff;border:1px solid #e4e4e4;box-shadow:0 1px 2px rgba(21,99,223,0.04),0 8px 20px -8px rgba(21,99,223,0.06);">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-xs font-semibold uppercase tracking-wider" style="color:#161e2d;">Anggota Keluarga / Penghuni</h4>
                    <button wire:click.prevent="addFamilyMember" type="button"
                        class="inline-flex items-center gap-1 text-xs font-medium px-2.5 py-1.5 rounded-lg transition-colors"
                        style="background:rgba(21,99,223,0.1);color:#161e2d;border:1px solid rgba(21,99,223,0.2);"
                        onmouseover="this.style.background='rgba(21,99,223,0.2)'" onmouseout="this.style.background='rgba(21,99,223,0.1)'">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Tambah Anggota
                    </button>
                </div>

                @if(empty($familyMembers))
                    <p class="text-xs italic text-center py-5 rounded-xl" style="color:#a3abb0;border:1px dashed #e4e4e4;">
                        Klik "Tambah Anggota" untuk menambah istri, anak, atau penghuni lain dalam satu KK.
                    </p>
                @endif

                <div class="space-y-4">
                    @foreach($familyMembers as $fi => $member)
                    <div class="rounded-xl p-4" style="border:1px solid #e4e4e4;background:#ffffff;" wire:key="fm-{{ $fi }}">
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-xs font-semibold" style="color:#161e2d;">Anggota #{{ $fi + 1 }}</span>
                            <button wire:click.prevent="removeFamilyMember({{ $fi }})" type="button" style="color:#c0453b;"
                                onmouseover="this.style.color='#c0453b'" onmouseout="this.style.color='#c0453b'">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            <div class="col-span-2 sm:col-span-3">
                                <input wire:model="familyMembers.{{ $fi }}.name" type="text" placeholder="Nama lengkap *"
                                    style="background:#ffffff;border:1px solid #e4e4e4;color:#161e2d;border-radius:0.5rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;"
                                    onfocus="this.style.borderColor='#1563df'" onblur="this.style.borderColor='#e4e4e4'">
                                @error("familyMembers.{$fi}.name") <p class="text-xs mt-0.5" style="color:#c0453b;">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-xs mb-1" style="color:#a3abb0;">Hubungan</label>
                                <select wire:model="familyMembers.{{ $fi }}.relationship"
                                    style="background:#ffffff;border:1px solid #e4e4e4;color:#161e2d;border-radius:0.5rem;padding:0.375rem 0.5rem;width:100%;font-size:0.8rem;outline:none;"
                                    onfocus="this.style.borderColor='#1563df'" onblur="this.style.borderColor='#e4e4e4'">
                                    <option value="istri">Istri</option>
                                    <option value="suami">Suami</option>
                                    <option value="anak">Anak</option>
                                    <option value="orang_tua">Orang Tua</option>
                                    <option value="mertua">Mertua</option>
                                    <option value="saudara">Saudara</option>
                                    <option value="lainnya">Lainnya</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs mb-1" style="color:#a3abb0;">Jenis Kelamin</label>
                                <select wire:model="familyMembers.{{ $fi }}.gender"
                                    style="background:#ffffff;border:1px solid #e4e4e4;color:#161e2d;border-radius:0.5rem;padding:0.375rem 0.5rem;width:100%;font-size:0.8rem;outline:none;"
                                    onfocus="this.style.borderColor='#1563df'" onblur="this.style.borderColor='#e4e4e4'">
                                    <option value="laki-laki">Laki-laki</option>
                                    <option value="perempuan">Perempuan</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs mb-1" style="color:#a3abb0;">Tgl. Lahir</label>
                                <input wire:model="familyMembers.{{ $fi }}.birth_date" type="date"
                                    style="background:#ffffff;border:1px solid #e4e4e4;color:#161e2d;border-radius:0.5rem;padding:0.375rem 0.5rem;width:100%;font-size:0.8rem;outline:none;"
                                    onfocus="this.style.borderColor='#1563df'" onblur="this.style.borderColor='#e4e4e4'">
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- ── Actions ── --}}
            <div class="flex items-center justify-end gap-3">
                <button wire:click="cancel" type="button"
                    class="px-5 py-2.5 rounded-xl text-sm font-medium transition-colors"
                    style="background:#f7f7f7;color:#161e2d;border:1px solid #d9d9d9;"
                    onmouseover="this.style.background='#e4e4e4'" onmouseout="this.style.background='#f7f7f7'">
                    Batal
                </button>
                <button wire:click="save" wire:loading.attr="disabled" type="button"
                    class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl text-sm font-semibold transition-colors disabled:opacity-60"
                    style="background:#1563df;color:#ffffff;"
                    onmouseover="this.style.background='#0e49a6'" onmouseout="this.style.background='#0e49a6'">
                    <span wire:loading.remove wire:target="save">
                        <svg class="w-4 h-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Simpan Data
                    </span>
                    <span wire:loading wire:target="save" class="flex items-center gap-2">
                        <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                        Menyimpan...
                    </span>
                </button>
            </div>

        </div>
    </div>
</div>
