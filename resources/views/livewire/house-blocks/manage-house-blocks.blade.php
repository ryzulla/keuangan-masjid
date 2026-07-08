<div>
    <x-slot name="header">
        <h2 style="font-size:1.4rem;font-weight:700;color:#111827;margin:0;">Data Blok Rumah</h2>
    </x-slot>

    <div style="padding:1.5rem;">
        <div style="display:flex; justify-content:flex-end; margin-bottom:1rem;">
            <button wire:click="openCreate"
                style="background:#111827;color:#ffffff;padding:8px 18px;border:none;border-radius:8px;cursor:pointer;font-weight:600;font-size:0.9rem;">
                + Tambah Blok
            </button>
        </div>

        <div style="padding:1.5rem;">

            @if(session('success'))
            <div style="background:#e3f1ea;border:1px solid #0e6d4f;color:#12805c;padding:12px 16px;border-radius:8px;margin-bottom:1rem;">
                {{ session('success') }}
            </div>
            @endif
            @if(session('error'))
            <div style="background:#f7e7e4;border:1px solid #f7e7e4;color:#c0453b;padding:12px 16px;border-radius:8px;margin-bottom:1rem;">
                {{ session('error') }}
            </div>
            @endif

            {{-- Stats --}}
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:1rem;margin-bottom:1.5rem;">
                <div style="background:#ffffff;border:1px solid #f5f6f8;border-radius:10px;padding:1rem;text-align:center;">
                    <div style="font-size:1.8rem;font-weight:700;color:#111827;">{{ $totalBlocks }}</div>
                    <div style="font-size:0.78rem;color:#667085;margin-top:2px;">Total Blok</div>
                </div>
                <div style="background:#ffffff;border:1px solid #f5f6f8;border-radius:10px;padding:1rem;text-align:center;">
                    <div style="font-size:1.8rem;font-weight:700;color:#12805c;">{{ $activeBlocks }}</div>
                    <div style="font-size:0.78rem;color:#667085;margin-top:2px;">Aktif</div>
                </div>
                <div style="background:#ffffff;border:1px solid #f5f6f8;border-radius:10px;padding:1rem;text-align:center;">
                    <div style="font-size:1.8rem;font-weight:700;color:#2563eb;">{{ $occupiedBlocks }}</div>
                    <div style="font-size:0.78rem;color:#667085;margin-top:2px;">Dihuni</div>
                </div>
                <div style="background:#ffffff;border:1px solid #f5f6f8;border-radius:10px;padding:1rem;text-align:center;">
                    <div style="font-size:1.8rem;font-weight:700;color:#c77d1a;">{{ $activeBlocks - $occupiedBlocks }}</div>
                    <div style="font-size:0.78rem;color:#667085;margin-top:2px;">Kosong</div>
                </div>
            </div>

            {{-- Inline Create/Edit Form --}}
            @if($showCreateForm || $showEditForm)
            <div style="background:#ffffff;border:1px solid #111827;border-radius:12px;padding:1.25rem;margin-bottom:1.5rem;">
                <div style="font-size:1rem;font-weight:600;color:#111827;margin-bottom:1rem;">
                    {{ $showEditForm ? 'Edit Blok' : 'Tambah Blok Baru' }}
                </div>
                <form wire:submit.prevent="save">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                        <div>
                            <label style="font-size:0.8rem;color:#475467;display:block;margin-bottom:4px;">Huruf Blok <span style="color:#c0453b;">*</span></label>
                            <input type="text" wire:model="blockLetter" placeholder="Misal: A" maxlength="5"
                                style="width:100%;background:#ffffff;border:1px solid #e4e7ec;box-shadow:0 1px 2px rgba(16,24,40,0.04),0 8px 20px -8px rgba(16,24,40,0.06);color:#1d2939;padding:8px 10px;border-radius:6px;font-size:0.9rem;text-transform:uppercase;box-sizing:border-box;">
                            @error('blockLetter') <p style="color:#c0453b;font-size:0.75rem;margin-top:2px;">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label style="font-size:0.8rem;color:#475467;display:block;margin-bottom:4px;">Nomor Unit <span style="color:#c0453b;">*</span></label>
                            <input type="number" wire:model="blockNumber" placeholder="1–99" min="1" max="99"
                                style="width:100%;background:#ffffff;border:1px solid #e4e7ec;box-shadow:0 1px 2px rgba(16,24,40,0.04),0 8px 20px -8px rgba(16,24,40,0.06);color:#1d2939;padding:8px 10px;border-radius:6px;font-size:0.9rem;box-sizing:border-box;">
                            @error('blockNumber') <p style="color:#c0453b;font-size:0.75rem;margin-top:2px;">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label style="font-size:0.8rem;color:#475467;display:block;margin-bottom:4px;">Status</label>
                            <select wire:model="blockIsActive" style="width:100%;background:#ffffff;border:1px solid #e4e7ec;box-shadow:0 1px 2px rgba(16,24,40,0.04),0 8px 20px -8px rgba(16,24,40,0.06);color:#1d2939;padding:8px 10px;border-radius:6px;font-size:0.9rem;box-sizing:border-box;">
                                <option value="1">Aktif</option>
                                <option value="0">Nonaktif</option>
                            </select>
                        </div>
                        <div style="padding-top:22px;display:flex;gap:0.5rem;">
                            <button type="submit"
                                style="background:#111827;color:#ffffff;padding:9px 18px;border:none;border-radius:6px;cursor:pointer;font-weight:600;white-space:nowrap;font-size:0.9rem;">
                                Simpan
                            </button>
                            <button type="button" wire:click="cancelForm"
                                style="background:#eef0f3;border:1px solid #cdd2da;color:#475467;padding:9px 14px;border-radius:6px;cursor:pointer;white-space:nowrap;font-size:0.9rem;">
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
                    style="background:#ffffff;border:1px solid #e4e7ec;box-shadow:0 1px 2px rgba(16,24,40,0.04),0 8px 20px -8px rgba(16,24,40,0.06);color:#1d2939;padding:7px 12px;border-radius:6px;font-size:0.85rem;">
                    <option value="">Semua Blok</option>
                    @foreach($allLetters as $letter)
                    <option value="{{ $letter }}">Blok {{ $letter }}</option>
                    @endforeach
                </select>
                <select wire:model.live="filterStatus"
                    style="background:#ffffff;border:1px solid #e4e7ec;box-shadow:0 1px 2px rgba(16,24,40,0.04),0 8px 20px -8px rgba(16,24,40,0.06);color:#1d2939;padding:7px 12px;border-radius:6px;font-size:0.85rem;">
                    <option value="">Semua Status</option>
                    <option value="dihuni">Dihuni</option>
                    <option value="kosong">Kosong</option>
                    <option value="nonaktif">Nonaktif</option>
                </select>
                <div style="display:flex;align-items:center;gap:1rem;margin-left:auto;font-size:0.78rem;color:#98a2b3;">
                    <span style="display:flex;align-items:center;gap:5px;">
                        <span style="width:8px;height:8px;border-radius:50%;background:#12805c;display:inline-block;"></span>Dihuni
                    </span>
                    <span style="display:flex;align-items:center;gap:5px;">
                        <span style="width:8px;height:8px;border-radius:50%;background:#111827;display:inline-block;"></span>Kosong
                    </span>
                    <span style="display:flex;align-items:center;gap:5px;">
                        <span style="width:8px;height:8px;border-radius:50%;background:#d0d5dd;display:inline-block;"></span>Nonaktif
                    </span>
                </div>
            </div>

            {{-- Block groups by letter --}}
            @forelse($blocksByLetter as $letter => $letterBlocks)
            <div style="margin-bottom:1.5rem;" wire:key="letter-{{ $letter }}">
                <div style="font-size:0.8rem;color:#667085;text-transform:uppercase;letter-spacing:.08em;margin-bottom:0.6rem;display:flex;align-items:center;gap:0.5rem;">
                    <span style="background:#ffffff;border:1px solid #f5f6f8;padding:2px 10px;border-radius:6px;color:#111827;">Blok {{ $letter }}</span>
                    <span style="color:#98a2b3;">{{ $letterBlocks->count() }} unit</span>
                </div>
                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:0.75rem;">
                    @foreach($letterBlocks as $block)
                    @php
                    $assignments = $block->currentAssignments ?? collect();
                    $owner = $assignments->firstWhere('ownership_type', 'pemilik');
                    $tenant = $assignments->first(fn($a) => in_array($a->ownership_type, ['kontrak', 'kos']));
                    $occupied = $assignments->isNotEmpty();
                    $isActive = $block->is_active;
                    $borderColor = !$isActive ? '#f7e7e4' : ($occupied ? '#e3f1ea' : '#f5f6f8');
                    $barColor = !$isActive ? '#f7e7e4' : ($occupied ? '#0e6d4f' : '#f5f6f8');
                    $codeColor = !$isActive ? '#cdd2da' : '#111827';
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
                                <span style="font-size:0.65rem;color:#c0453b;background:#f7e7e4;padding:1px 5px;border-radius:4px;">Off</span>
                                @endif
                            </div>

                            @if($owner)
                            <div style="font-size:0.75rem;color:#12805c;margin-bottom:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                <span style="color:#98a2b3;">P:</span> {{ $owner->resident->name ?? '—' }}
                            </div>
                            @else
                            <div style="font-size:0.72rem;color:#f7e7e4;">Pemilik belum ditetapkan</div>
                            @endif

                            @if($tenant)
                            <div style="font-size:0.75rem;color:#2563eb;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                <span style="color:#98a2b3;">S:</span> {{ $tenant->resident->name ?? '—' }}
                                <span style="color:#98a2b3;font-size:0.68rem;">({{ ucfirst($tenant->ownership_type) }})</span>
                            </div>
                            @endif

                            <div style="margin-top:0.6rem;display:flex;gap:0.4rem;">
                                <a href="{{ route('house-blocks.show', $block) }}"
                                    style="flex:1;text-align:center;background:#ffffff;border:1px solid #e4e7ec;box-shadow:0 1px 2px rgba(16,24,40,0.04),0 8px 20px -8px rgba(16,24,40,0.06);color:#475467;padding:4px 8px;border-radius:5px;font-size:0.75rem;text-decoration:none;">
                                    Detail
                                </a>
                                <button wire:click="openEdit({{ $block->id }})"
                                    style="flex:1;background:#ffffff;border:1px solid #e4e7ec;box-shadow:0 1px 2px rgba(16,24,40,0.04),0 8px 20px -8px rgba(16,24,40,0.06);color:#111827;padding:4px 8px;border-radius:5px;cursor:pointer;font-size:0.75rem;">
                                    Edit
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @empty
            <div style="text-align:center;padding:3rem;color:#98a2b3;background:#ffffff;border:1px solid #f5f6f8;border-radius:12px;">
                Tidak ada blok yang sesuai filter.
            </div>
            @endforelse

        </div>
    </div>
