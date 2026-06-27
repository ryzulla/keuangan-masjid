<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Data Penghuni Perumahan
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session()->has('success') && !$isModalOpen)
                <div role="alert" class="alert alert-success shadow-lg mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if (session()->has('error'))
                <div role="alert" class="alert alert-error shadow-lg mb-4">
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            {{-- Filters & Search --}}
            <div class="card bg-base-100 shadow mb-4">
                <div class="card-body py-4">
                    <div class="flex flex-wrap gap-3 items-end justify-between">
                        <div class="flex flex-wrap gap-2 flex-1">
                            <div class="form-control">
                                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama penghuni..." class="input input-bordered input-sm w-56" />
                            </div>
                            <div class="form-control">
                                <select wire:model.live="filterBlock" class="select select-bordered select-sm">
                                    <option value="">Semua Blok</option>
                                    @foreach($houseBlocks as $block)
                                        <option value="{{ $block->id }}">{{ $block->block_code }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-control">
                                <select wire:model.live="filterOwnership" class="select select-bordered select-sm">
                                    <option value="">Semua Kepemilikan</option>
                                    <option value="pemilik">Pemilik</option>
                                    <option value="kontrak">Kontrak</option>
                                    <option value="kos">Kos</option>
                                </select>
                            </div>
                            <div class="form-control">
                                <select wire:model.live="filterOccupancy" class="select select-bordered select-sm">
                                    <option value="">Semua Status Hunian</option>
                                    <option value="dihuni">Dihuni</option>
                                    <option value="kosong">Kosong</option>
                                </select>
                            </div>
                        </div>
                        <button wire:click="create()" class="btn btn-primary btn-sm">+ Tambah Penghuni</button>
                    </div>
                </div>
            </div>

            {{-- Table --}}
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <div class="overflow-x-auto">
                        <table class="table table-sm table-zebra w-full">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Blok</th>
                                    <th>Nama Penghuni</th>
                                    <th>Telepon</th>
                                    <th>WhatsApp</th>
                                    <th>Kepemilikan</th>
                                    <th>Status Hunian</th>
                                    <th>Aktif</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($residents as $i => $resident)
                                    <tr class="hover" wire:key="resident-{{ $resident->id }}">
                                        <td>{{ $residents->firstItem() + $i }}</td>
                                        <td>
                                            @if($resident->houseBlock)
                                                <span class="badge badge-outline badge-sm font-mono">{{ $resident->houseBlock->block_code }}</span>
                                            @else
                                                <span class="text-gray-400 text-xs">—</span>
                                            @endif
                                        </td>
                                        <td class="font-medium">{{ $resident->name }}</td>
                                        <td class="text-sm">{{ $resident->phone ?: '—' }}</td>
                                        <td class="text-sm">{{ $resident->whatsapp ?: '—' }}</td>
                                        <td>
                                            <span @class([
                                                'badge badge-sm',
                                                'badge-success' => $resident->ownership_status === 'pemilik',
                                                'badge-warning' => $resident->ownership_status === 'kontrak',
                                                'badge-info' => $resident->ownership_status === 'kos',
                                            ])>
                                                {{ ucfirst($resident->ownership_status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span @class([
                                                'badge badge-sm badge-outline',
                                                'badge-success' => $resident->occupancy_status === 'dihuni',
                                                'badge-ghost' => $resident->occupancy_status === 'kosong',
                                            ])>
                                                {{ $resident->occupancy_status === 'dihuni' ? 'Dihuni' : 'Kosong' }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($resident->is_active)
                                                <span class="badge badge-success badge-xs">Aktif</span>
                                            @else
                                                <span class="badge badge-ghost badge-xs">Nonaktif</span>
                                            @endif
                                        </td>
                                        <td class="whitespace-nowrap space-x-1">
                                            <button wire:click="showDetail({{ $resident->id }})" class="btn btn-info btn-xs">Detail</button>
                                            <button wire:click="edit({{ $resident->id }})" class="btn btn-warning btn-xs">Edit</button>
                                            <button wire:click="confirmDelete({{ $resident->id }})" class="btn btn-error btn-xs">Hapus</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="9" class="text-center py-8 text-gray-400">Belum ada data penghuni.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">{{ $residents->links() }}</div>
                </div>
            </div>

            {{-- Add/Edit Modal --}}
            <div class="modal {{ $isModalOpen ? 'modal-open' : '' }}" x-data x-on:keydown.escape.window="$wire.closeModal()">
                <div class="modal-box w-11/12 max-w-2xl" @click.stop>
                    <h3 class="font-bold text-lg">{{ $selected_id ? 'Edit Data Penghuni' : 'Tambah Penghuni Baru' }}</h3>

                    @if($errors->any() || session()->has('modal_error'))
                        <div role="alert" class="alert alert-warning mt-3">
                            @if(session()->has('modal_error'))
                                <span>{{ session('modal_error') }}</span>
                            @else
                                <ul class="list-disc pl-5 text-sm">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                            @endif
                        </div>
                    @endif

                    <form wire:submit="store" class="space-y-4 mt-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-control md:col-span-2">
                                <label class="label"><span class="label-text">Nama Penghuni <span class="text-error">*</span></span></label>
                                <input type="text" wire:model="name" class="input input-bordered w-full" placeholder="Nama lengkap">
                                @error('name')<span class="text-error text-xs">{{ $message }}</span>@enderror
                            </div>

                            <div class="form-control">
                                <label class="label"><span class="label-text">Blok Rumah</span></label>
                                <select wire:model="house_block_id" class="select select-bordered w-full">
                                    <option value="">-- Pilih Blok --</option>
                                    @foreach($houseBlocks as $block)
                                        <option value="{{ $block->id }}">{{ $block->block_code }}</option>
                                    @endforeach
                                </select>
                                @error('house_block_id')<span class="text-error text-xs">{{ $message }}</span>@enderror
                            </div>

                            @can('manage-admin')
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">NIK <span class="badge badge-xs badge-warning">Rahasia</span></span>
                                </label>
                                <input type="text" wire:model="nik" class="input input-bordered w-full font-mono" placeholder="16 digit NIK" maxlength="16">
                                @error('nik')<span class="text-error text-xs">{{ $message }}</span>@enderror
                            </div>
                            @endcan

                            <div class="form-control">
                                <label class="label"><span class="label-text">Telepon</span></label>
                                <input type="text" wire:model="phone" class="input input-bordered w-full" placeholder="08xxxxxxxxxx">
                                @error('phone')<span class="text-error text-xs">{{ $message }}</span>@enderror
                            </div>

                            <div class="form-control">
                                <label class="label"><span class="label-text">WhatsApp</span></label>
                                <input type="text" wire:model="whatsapp" class="input input-bordered w-full" placeholder="08xxxxxxxxxx">
                                @error('whatsapp')<span class="text-error text-xs">{{ $message }}</span>@enderror
                            </div>

                            <div class="form-control md:col-span-2">
                                <label class="label"><span class="label-text">Email</span></label>
                                <input type="email" wire:model="email" class="input input-bordered w-full" placeholder="email@contoh.com">
                                @error('email')<span class="text-error text-xs">{{ $message }}</span>@enderror
                            </div>

                            <div class="form-control">
                                <label class="label"><span class="label-text">Status Kepemilikan <span class="text-error">*</span></span></label>
                                <select wire:model="ownership_status" class="select select-bordered w-full">
                                    <option value="pemilik">Pemilik</option>
                                    <option value="kontrak">Kontrak</option>
                                    <option value="kos">Kos</option>
                                </select>
                                @error('ownership_status')<span class="text-error text-xs">{{ $message }}</span>@enderror
                            </div>

                            <div class="form-control">
                                <label class="label"><span class="label-text">Status Hunian <span class="text-error">*</span></span></label>
                                <select wire:model="occupancy_status" class="select select-bordered w-full">
                                    <option value="dihuni">Dihuni</option>
                                    <option value="kosong">Kosong</option>
                                </select>
                                @error('occupancy_status')<span class="text-error text-xs">{{ $message }}</span>@enderror
                            </div>

                            <div class="form-control">
                                <label class="label"><span class="label-text">Tanggal Masuk</span></label>
                                <input type="date" wire:model="resident_since" class="input input-bordered w-full">
                                @error('resident_since')<span class="text-error text-xs">{{ $message }}</span>@enderror
                            </div>

                            <div class="form-control">
                                <label class="label cursor-pointer">
                                    <span class="label-text">Status Aktif</span>
                                    <input type="checkbox" wire:model="is_active" class="toggle toggle-success">
                                </label>
                            </div>

                            <div class="form-control md:col-span-2">
                                <label class="label"><span class="label-text">Catatan</span></label>
                                <textarea wire:model="notes" class="textarea textarea-bordered w-full" rows="2" placeholder="Catatan tambahan..."></textarea>
                            </div>
                        </div>

                        <div class="modal-action">
                            <button type="button" wire:click="closeModal()" class="btn btn-ghost">Batal</button>
                            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                <span wire:loading.remove>Simpan</span>
                                <span wire:loading class="loading loading-spinner loading-sm"></span>
                            </button>
                        </div>
                    </form>
                </div>
                <form wire:click="closeModal" class="modal-backdrop"><button type="button">close</button></form>
            </div>

            {{-- Detail Modal --}}
            <div class="modal {{ $isDetailOpen ? 'modal-open' : '' }}" x-data x-on:keydown.escape.window="$wire.closeDetail()">
                <div class="modal-box w-11/12 max-w-2xl" @click.stop>
                    @if($detailResident)
                        <h3 class="font-bold text-lg">Detail Penghuni: {{ $detailResident->name }}</h3>
                        <div class="grid grid-cols-2 gap-4 mt-4 text-sm">
                            <div>
                                <span class="font-semibold text-gray-500">Blok:</span>
                                <p class="font-mono text-lg">{{ $detailResident->houseBlock?->block_code ?? '—' }}</p>
                            </div>
                            <div>
                                <span class="font-semibold text-gray-500">Status:</span>
                                <p>
                                    <span class="badge badge-sm {{ $detailResident->is_active ? 'badge-success' : 'badge-ghost' }}">
                                        {{ $detailResident->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </p>
                            </div>
                            <div>
                                <span class="font-semibold text-gray-500">Telepon:</span>
                                <p>{{ $detailResident->phone ?: '—' }}</p>
                            </div>
                            <div>
                                <span class="font-semibold text-gray-500">WhatsApp:</span>
                                <p>{{ $detailResident->whatsapp ?: '—' }}</p>
                            </div>
                            <div>
                                <span class="font-semibold text-gray-500">Email:</span>
                                <p>{{ $detailResident->email ?: '—' }}</p>
                            </div>
                            <div>
                                <span class="font-semibold text-gray-500">Kepemilikan:</span>
                                <p>{{ ucfirst($detailResident->ownership_status) }}</p>
                            </div>
                            <div>
                                <span class="font-semibold text-gray-500">Status Hunian:</span>
                                <p>{{ $detailResident->occupancy_status === 'dihuni' ? 'Dihuni' : 'Kosong' }}</p>
                            </div>
                            <div>
                                <span class="font-semibold text-gray-500">Tanggal Masuk:</span>
                                <p>{{ $detailResident->resident_since?->format('d/m/Y') ?? '—' }}</p>
                            </div>
                            @can('manage-admin')
                            <div class="col-span-2">
                                <span class="font-semibold text-gray-500">NIK <span class="badge badge-xs badge-warning">Rahasia</span>:</span>
                                <p class="font-mono">{{ $detailResident->nik ?: '—' }}</p>
                            </div>
                            @endcan
                            @if($detailResident->notes)
                            <div class="col-span-2">
                                <span class="font-semibold text-gray-500">Catatan:</span>
                                <p class="mt-1">{{ $detailResident->notes }}</p>
                            </div>
                            @endif
                        </div>

                        {{-- IPL Summary --}}
                        @if($detailResident->iplBillings->count() > 0)
                            <div class="divider">Riwayat IPL</div>
                            <div class="overflow-x-auto">
                                <table class="table table-xs">
                                    <thead><tr><th>Periode</th><th>Tagihan</th><th>Terbayar</th><th>Status</th></tr></thead>
                                    <tbody>
                                        @foreach($detailResident->iplBillings->take(6) as $billing)
                                            <tr>
                                                <td>{{ $billing->period?->period_label ?? '—' }}</td>
                                                <td>Rp {{ number_format($billing->total_amount, 0, ',', '.') }}</td>
                                                <td>Rp {{ number_format($billing->total_paid, 0, ',', '.') }}</td>
                                                <td>
                                                    <span @class(['badge badge-xs', 'badge-success'=>$billing->status==='paid', 'badge-warning'=>$billing->status==='partial', 'badge-error'=>$billing->status==='unpaid'])>
                                                        {{ $billing->status === 'paid' ? 'Lunas' : ($billing->status === 'partial' ? 'Sebagian' : 'Belum Bayar') }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    @endif
                    <div class="modal-action">
                        <button wire:click="closeDetail()" class="btn btn-ghost">Tutup</button>
                    </div>
                </div>
                <form wire:click="closeDetail" class="modal-backdrop"><button type="button">close</button></form>
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
    (function() {
        let listenersAttached = false;
        function initResidentListeners() {
            if (!listenersAttached && window.Livewire && window.Swal) {
                Livewire.on('show-resident-delete-confirmation', (event) => {
                    let id = event.id ?? (event[0]?.id);
                    Swal.fire({
                        title: 'Hapus Penghuni?', text: 'Data ini akan dihapus permanen!',
                        icon: 'warning', showCancelButton: true,
                        confirmButtonColor: '#d33', cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, Hapus!', cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed && id !== undefined) @this.call('delete', id);
                    });
                });
                Livewire.on('residentDeleted', () => Swal.fire({ title: 'Terhapus!', icon: 'success', timer: 2000, showConfirmButton: false }));
                Livewire.on('deleteFailed', (e) => Swal.fire('Gagal!', e.message ?? (e[0]?.message ?? 'Gagal menghapus.'), 'error'));
                listenersAttached = true;
            }
        }
        document.addEventListener('livewire:navigated', () => { listenersAttached = false; initResidentListeners(); });
        document.addEventListener('livewire:initialized', initResidentListeners);
    })();
    </script>
    @endpush
</div>
