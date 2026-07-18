<div>
    <div style="padding:1.5rem;">
        {{-- Header (samakan dengan Data Penghuni: kartu hero pine) --}}
        <div class="rounded-2xl p-6 mb-5 pp-hero" style="background:#ffffff;border:1px solid rgba(22,74,64,0.35);">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h3 class="font-bold text-lg" style="color:#17231E;font-family:'Fraunces',Georgia,serif;">Data Blok Rumah</h3>
                    <p class="text-sm mt-1" style="color:#17231E;">Kelola blok & unit rumah perumahan blok A-1 s/d P-9</p>
                </div>
                <button wire:click="openCreate"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold transition-colors shrink-0"
                    style="background:#164A40;color:#ffffff;">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Blok
                </button>
            </div>
        </div>

        <div style="padding:1.5rem;">

            @if(session('success'))
            <div style="background:#E4F1EB;border:1px solid #0E6844;color:#12805c;padding:12px 16px;border-radius:8px;margin-bottom:1rem;">
                {{ session('success') }}
            </div>
            @endif
            @if(session('error'))
            <div style="background:#F6E7E2;border:1px solid #F6E7E2;color:#B0402C;padding:12px 16px;border-radius:8px;margin-bottom:1rem;">
                {{ session('error') }}
            </div>
            @endif

            {{-- Stats --}}
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:1rem;margin-bottom:1.5rem;">
                <div style="background:#ffffff;border:1px solid #F1F3EC;border-radius:10px;padding:1rem;text-align:center;">
                    <div style="font-size:1.8rem;font-weight:700;color:#17231E;">{{ $totalBlocks }}</div>
                    <div style="font-size:0.78rem;color:#586359;margin-top:2px;">Total Blok</div>
                </div>
                <div style="background:#ffffff;border:1px solid #F1F3EC;border-radius:10px;padding:1rem;text-align:center;">
                    <div style="font-size:1.8rem;font-weight:700;color:#12805c;">{{ $activeBlocks }}</div>
                    <div style="font-size:0.78rem;color:#586359;margin-top:2px;">Aktif</div>
                </div>
                <div style="background:#ffffff;border:1px solid #F1F3EC;border-radius:10px;padding:1rem;text-align:center;">
                    <div style="font-size:1.8rem;font-weight:700;color:#164A40;">{{ $occupiedBlocks }}</div>
                    <div style="font-size:0.78rem;color:#586359;margin-top:2px;">Dihuni</div>
                </div>
                <div style="background:#ffffff;border:1px solid #F1F3EC;border-radius:10px;padding:1rem;text-align:center;">
                    <div style="font-size:1.8rem;font-weight:700;color:#0d9488;">{{ $rentedBlocks }}</div>
                    <div style="font-size:0.78rem;color:#586359;margin-top:2px;">Disewa</div>
                </div>
                <div style="background:#ffffff;border:1px solid #F1F3EC;border-radius:10px;padding:1rem;text-align:center;">
                    <div style="font-size:1.8rem;font-weight:700;color:#A9741A;">{{ $activeBlocks - $occupiedBlocks }}</div>
                    <div style="font-size:0.78rem;color:#586359;margin-top:2px;">Kosong</div>
                </div>
            </div>

            {{-- Inline Create/Edit Form --}}
            @if($showCreateForm || $showEditForm)
            <div style="background:#ffffff;border:1px solid #164A40;border-radius:12px;padding:1.25rem;margin-bottom:1.5rem;">
                <div style="font-size:1rem;font-weight:600;color:#17231E;margin-bottom:1rem;">
                    {{ $showEditForm ? 'Edit Blok' : 'Tambah Blok Baru' }}
                </div>
                <form wire:submit.prevent="save">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                        <div>
                            <label style="font-size:0.8rem;color:#586359;display:block;margin-bottom:4px;">Huruf Blok <span style="color:#B0402C;">*</span></label>
                            <input type="text" wire:model="blockLetter" placeholder="Misal: A" maxlength="5"
                                style="width:100%;background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);color:#17231E;padding:8px 10px;border-radius:6px;font-size:0.9rem;text-transform:uppercase;box-sizing:border-box;">
                            @error('blockLetter') <p style="color:#B0402C;font-size:0.75rem;margin-top:2px;">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label style="font-size:0.8rem;color:#586359;display:block;margin-bottom:4px;">Nomor Unit <span style="color:#B0402C;">*</span></label>
                            <input type="number" wire:model="blockNumber" placeholder="1–99" min="1" max="99"
                                style="width:100%;background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);color:#17231E;padding:8px 10px;border-radius:6px;font-size:0.9rem;box-sizing:border-box;">
                            @error('blockNumber') <p style="color:#B0402C;font-size:0.75rem;margin-top:2px;">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label style="font-size:0.8rem;color:#586359;display:block;margin-bottom:4px;">Status</label>
                            <select wire:model="blockIsActive" style="width:100%;background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);color:#17231E;padding:8px 10px;border-radius:6px;font-size:0.9rem;box-sizing:border-box;">
                                <option value="1">Aktif</option>
                                <option value="0">Nonaktif</option>
                            </select>
                        </div>
                        <div style="padding-top:22px;display:flex;gap:0.5rem;">
                            <button type="submit"
                                style="background:#164A40;color:#ffffff;padding:9px 18px;border:none;border-radius:6px;cursor:pointer;font-weight:600;white-space:nowrap;font-size:0.9rem;">
                                Simpan
                            </button>
                            <button type="button" wire:click="cancelForm"
                                style="background:#F1F3EC;border:1px solid #D8D6C9;color:#586359;padding:9px 14px;border-radius:6px;cursor:pointer;white-space:nowrap;font-size:0.9rem;">
                                Batal
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            @endif

            {{-- Filters --}}
            <div style="display:flex;gap:0.75rem;align-items:center;margin-bottom:1.25rem;flex-wrap:wrap;">
                <select wire:model.live="filterLetter"
                    style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);color:#17231E;padding:7px 12px;border-radius:6px;font-size:0.85rem;">
                    <option value="">Semua Blok</option>
                    @foreach($allLetters as $letter)
                    <option value="{{ $letter }}">Blok {{ $letter }}</option>
                    @endforeach
                </select>
                <select wire:model.live="filterStatus"
                    style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);color:#17231E;padding:7px 12px;border-radius:6px;font-size:0.85rem;">
                    <option value="">Semua Status</option>
                    <option value="dihuni">Dihuni</option>
                    <option value="disewa">Disewa</option>
                    <option value="kosong">Kosong</option>
                    <option value="nonaktif">Nonaktif</option>
                </select>
                <div style="display:flex;align-items:center;gap:1rem;margin-left:auto;font-size:0.78rem;color:#909A8F;">
                    <span style="display:flex;align-items:center;gap:5px;">
                        <span style="width:8px;height:8px;border-radius:50%;background:#12805c;display:inline-block;"></span>Dihuni
                    </span>
                    <span style="display:flex;align-items:center;gap:5px;">
                        <span style="width:8px;height:8px;border-radius:50%;background:#164A40;display:inline-block;"></span>Kosong
                    </span>
                    <span style="display:flex;align-items:center;gap:5px;">
                        <span style="width:8px;height:8px;border-radius:50%;background:#D8D6C9;display:inline-block;"></span>Nonaktif
                    </span>
                </div>
            </div>

            {{-- Block groups by letter --}}
            @forelse($blocksByLetter as $letter => $letterBlocks)
            <div style="margin-bottom:1.5rem;" wire:key="letter-{{ $letter }}">
                <div style="font-size:0.8rem;color:#586359;text-transform:uppercase;letter-spacing:.08em;margin-bottom:0.6rem;display:flex;align-items:center;gap:0.5rem;">
                    <span style="background:#ffffff;border:1px solid #F1F3EC;padding:2px 10px;border-radius:6px;color:#17231E;">Blok {{ $letter }}</span>
                    <span style="color:#909A8F;">{{ $letterBlocks->count() }} unit</span>
                </div>
                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:0.75rem;">
                    @foreach($letterBlocks as $block)
                    @php
                    $assignments = $block->currentAssignments ?? collect();
                    $owner = $assignments->firstWhere('ownership_type', 'pemilik');
                    $tenant = $assignments->first(fn($a) => in_array($a->ownership_type, ['kontrak', 'kos']));
                    $occupied = $assignments->isNotEmpty();
                    $isActive = $block->is_active;
                    $borderColor = !$isActive ? '#F6E7E2' : ($occupied ? '#E4F1EB' : '#F1F3EC');
                    $barColor = !$isActive ? '#F6E7E2' : ($occupied ? '#0E6844' : '#F1F3EC');
                    $codeColor = !$isActive ? '#D8D6C9' : '#164A40';
                    @endphp
                    <div style="background:#ffffff;border:1px solid {{ $borderColor }};border-radius:10px;overflow:hidden;position:relative;"
                        wire:key="block-{{ $block->id }}">
                        <div style="height:3px;background:{{ $barColor }};"></div>
                        <div style="padding:0.75rem;">
                            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:0.5rem;">
                                <a href="{{ route('house-blocks.show', $block) }}"
                                    style="font-size:1.1rem;font-weight:700;color:{{ $codeColor }};text-decoration:none;">
                                    {{ $block->block_code }}
                                </a>
                                @if(!$isActive)
                                <span style="font-size:0.65rem;color:#B0402C;background:#F6E7E2;padding:1px 5px;border-radius:4px;">Off</span>
                                @endif
                            </div>

                            @if($owner)
                            <div style="font-size:0.75rem;color:#12805c;margin-bottom:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                <span style="color:#909A8F;">P:</span> {{ $owner->resident->name ?? '—' }}
                            </div>
                            @else
                            <div style="font-size:0.72rem;color:#F6E7E2;">Pemilik belum ditetapkan</div>
                            @endif

                            @if($tenant)
                            <div style="font-size:0.75rem;color:#164A40;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                <span style="color:#909A8F;">S:</span> {{ $tenant->resident->name ?? '—' }}
                                <span style="color:#909A8F;font-size:0.68rem;">({{ ucfirst($tenant->ownership_type) }})</span>
                            </div>
                            @endif

                            <div style="margin-top:0.6rem;display:flex;gap:0.4rem;">
                                <a href="{{ route('house-blocks.show', $block) }}"
                                    style="flex:1;text-align:center;background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);color:#586359;padding:4px 8px;border-radius:5px;font-size:0.75rem;text-decoration:none;">
                                    Detail
                                </a>
                                <button wire:click="openEdit({{ $block->id }})"
                                    style="flex:1;background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);color:#17231E;padding:4px 8px;border-radius:5px;cursor:pointer;font-size:0.75rem;">
                                    Edit
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @empty
            <div style="text-align:center;padding:3rem;color:#909A8F;background:#ffffff;border:1px solid #F1F3EC;border-radius:12px;">
                Tidak ada blok yang sesuai filter.
            </div>
            @endforelse

        </div>
    </div>
