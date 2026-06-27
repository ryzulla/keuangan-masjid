<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Manajemen Program/Kampanye
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
            @if (session()->has('error') && !$isModalOpen)
                <div role="alert" class="alert alert-error shadow-lg mb-4"><span>{{ session('error') }}</span></div>
            @endif

            {{-- Org Tabs --}}
            <div class="tabs tabs-boxed mb-4">
                <button wire:click="switchTab('dkm')" class="tab {{ $activeOrgTab === 'dkm' ? 'tab-active' : '' }}">
                    🕌 Program DKM Masjid
                </button>
                <button wire:click="switchTab('perumahan')" class="tab {{ $activeOrgTab === 'perumahan' ? 'tab-active' : '' }}">
                    🏘️ Program Perumahan
                </button>
            </div>

            <div class="mb-4 text-right">
                <button wire:click="create()" class="btn btn-primary btn-sm">
                    + Tambah Program {{ $activeOrgTab === 'perumahan' ? 'Perumahan' : 'DKM' }}
                </button>
            </div>

            <div class="card bg-base-100 shadow-xl border border-gray-200 dark:border-gray-700">
                <div class="card-body">
                    <div class="overflow-x-auto">
                        <table class="table w-full">
                            <thead>
                                <tr>
                                    <th>Gambar</th>
                                    <th>Nama Program</th>
                                    <th>Target</th>
                                    <th>Terkumpul</th>
                                    <th>Progress</th>
                                    <th>Status</th>
                                    <th>Sumber Dana</th>
                                    <th>Periode</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($campaigns->items() as $campaign)
                                    <tr class="hover" wire:key="campaign-row-{{ $campaign->id }}">
                                        <td>
                                            @if($campaign->image)
                                                <div class="avatar"><div class="w-12 h-12 rounded"><img src="{{ Storage::url($campaign->image) }}" alt="{{ $campaign->name }}" /></div></div>
                                            @else
                                                <div class="avatar placeholder"><div class="bg-neutral text-neutral-content rounded w-12 h-12 flex items-center justify-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" /></svg>
                                                </div></div>
                                            @endif
                                        </td>
                                        <td>{{ $campaign->name }}</td>
                                        <td>Rp {{ number_format($campaign->target_amount ?? 0, 0, ',', '.') }}</td>
                                        <td class="font-semibold">Rp {{ number_format($campaign->transactions_sum_amount ?? 0, 0, ',', '.') }}</td>
                                        <td>
                                            @php
                                                $target = (float)($campaign->target_amount ?? 0);
                                                $raised = (float)($campaign->transactions_sum_amount ?? 0);
                                                $progress = ($target > 0) ? min(100, ($raised / $target) * 100) : ($raised > 0 ? 100 : 0);
                                            @endphp
                                            <div class="tooltip tooltip-bottom w-full" data-tip="{{ number_format($progress, 1) }}%">
                                                <progress class="progress {{ $progress >= 100 ? 'progress-success' : 'progress-warning' }} w-full" value="{{ $progress }}" max="100"></progress>
                                            </div>
                                        </td>
                                        <td>
                                            <span @class(['badge badge-sm badge-outline', 'badge-success' => $campaign->status == 'active', 'badge-info' => $campaign->status == 'completed', 'badge-error' => $campaign->status == 'cancelled'])>
                                                {{ $campaign->status }}
                                            </span>
                                        </td>
                                        <td class="text-xs">
                                            @if($campaign->sourceAccount)
                                                <span class="badge badge-ghost badge-xs">{{ $campaign->sourceAccount->name }}</span>
                                            @else
                                                <span class="text-gray-400">—</span>
                                            @endif
                                        </td>
                                        <td class="whitespace-nowrap text-xs">
                                            {{ optional($campaign->start_date)->format('d/m/y') }}<br>
                                            {{ optional($campaign->end_date)->format('d/m/y') ?? '...' }}
                                        </td>
                                        <td class="space-x-1 whitespace-nowrap">
                                            <button wire:click="edit({{ $campaign->id }})" class="btn btn-warning btn-xs">Edit</button>
                                            <button wire:click.prevent="confirmDelete({{ $campaign->id }})" class="btn btn-error btn-xs">Hapus</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="9" class="text-center py-6 text-gray-400">
                                        Belum ada program {{ $activeOrgTab === 'perumahan' ? 'Perumahan' : 'DKM' }}.
                                    </td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">{{ $campaigns->links() }}</div>
                </div>
            </div>

            {{-- Modal --}}
            <div class="modal {{ $isModalOpen ? 'modal-open' : '' }}" id="campaign-modal"
                x-data="{ isModalOpen: @entangle('isModalOpen') }"
                x-show="isModalOpen"
                x-on:keydown.escape.window="isModalOpen = false; @this.call('closeModal')"
                x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                style="display: none;">
                <div class="modal-box w-11/12 max-w-3xl" @click.stop
                     x-watch="isModalOpen, (value) => {
                         let selectedId = $wire.get('selected_id');
                         let editorId = 'campaignDescription' + (selectedId ? 'Edit' + selectedId : 'Create');
                         if (value) {
                             $nextTick(() => { initCkEditor(editorId, $wire.get('description')); });
                         } else {
                             destroyCkInstance(editorId);
                         }
                     }">
                    <h3 class="font-bold text-lg">
                        {{ $selected_id ? 'Edit Program' : 'Buat Program Baru' }}
                        <span class="badge badge-sm {{ $organization_type === 'perumahan' ? 'badge-primary' : 'badge-success' }} ml-2">
                            {{ $organization_type === 'perumahan' ? 'Perumahan' : 'DKM' }}
                        </span>
                    </h3>

                    @if ($errors->any() || session()->has('modal_error'))
                        <div role="alert" class="alert alert-warning mt-4 text-sm">
                            @if(session()->has('modal_error'))
                                <span>{{ session('modal_error') }}</span>
                            @else
                                <ul class="list-disc pl-5 text-xs">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                            @endif
                        </div>
                    @endif

                    <form wire:submit="store" class="space-y-4 mt-4">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-control md:col-span-2">
                                <label class="label"><span class="label-text">Nama Program<span class="text-error">*</span></span></label>
                                <input type="text" wire:model="name" class="input input-bordered w-full">
                                @error('name')<label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>@enderror
                            </div>

                            <div class="form-control">
                                <label class="label"><span class="label-text">Jenis Program<span class="text-error">*</span></span></label>
                                <select wire:model="organization_type" class="select select-bordered w-full">
                                    <option value="dkm">DKM Masjid</option>
                                    <option value="perumahan">Perumahan</option>
                                </select>
                            </div>

                            <div class="form-control">
                                <label class="label"><span class="label-text">Sumber Dana Awal (Opsional)</span></label>
                                <select wire:model="source_account_id" class="select select-bordered w-full">
                                    <option value="">-- Tidak ada / Donasi Murni --</option>
                                    @foreach($sourceAccounts as $acc)
                                        <option value="{{ $acc->id }}">{{ $acc->name }}</option>
                                    @endforeach
                                </select>
                                @error('source_account_id')<span class="text-error text-xs">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        <div class="form-control w-full" wire:ignore>
                            <label class="label"><span class="label-text">Deskripsi Lengkap (Opsional)</span></label>
                            <textarea id="campaignDescription{{ $selected_id ? 'Edit'.$selected_id : 'Create' }}" class="hidden"></textarea>
                            @error('description')<label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>@enderror
                        </div>

                        <div class="form-control w-full">
                            <label class="label"><span class="label-text">Gambar Utama (Opsional)</span></label>
                            <input type="file" wire:model="image" class="file-input file-input-bordered file-input-sm w-full max-w-xs">
                            <div wire:loading wire:target="image" class="text-xs text-info mt-1">Mengunggah...</div>
                            @error('image')<label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>@enderror
                            <div class="mt-2">
                                @if ($image && !$errors->has('image'))
                                    <img src="{{ $image->temporaryUrl() }}" class="h-24 w-auto rounded border p-1 object-cover">
                                @elseif ($existingImage)
                                    <div class="inline-block relative group">
                                        <img src="{{ Storage::url($existingImage) }}" class="h-24 w-auto rounded border p-1 object-cover">
                                        <button type="button" wire:click="removeImage" class="btn btn-xs btn-circle btn-error absolute -top-2 -right-2 opacity-0 group-hover:opacity-100 transition-opacity">✕</button>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-control w-full">
                            <label class="label"><span class="label-text">Target Donasi (Rp, Opsional)</span></label>
                            <input type="number" step="any" wire:model="target_amount" class="input input-bordered w-full" placeholder="0">
                            @error('target_amount')<label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>@enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-control w-full">
                                <label class="label"><span class="label-text">Tanggal Mulai<span class="text-error">*</span></span></label>
                                <input type="date" wire:model="start_date" class="input input-bordered w-full">
                                @error('start_date')<label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>@enderror
                            </div>
                            <div class="form-control w-full">
                                <label class="label"><span class="label-text">Tanggal Selesai (Opsional)</span></label>
                                <input type="date" wire:model="end_date" class="input input-bordered w-full">
                                @error('end_date')<label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>@enderror
                            </div>
                        </div>

                        <div class="form-control w-full">
                            <label class="label"><span class="label-text">Status<span class="text-error">*</span></span></label>
                            <select wire:model="status" class="select select-bordered w-full">
                                <option value="active">Aktif</option>
                                <option value="completed">Selesai</option>
                                <option value="cancelled">Dibatalkan</option>
                            </select>
                        </div>

                        <div class="modal-action">
                            <button type="button" wire:click="closeModal()" class="btn btn-ghost">Batal</button>
                            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                <span wire:loading.remove>Simpan Program</span>
                                <span wire:loading class="loading loading-spinner loading-sm"></span>
                            </button>
                        </div>
                    </form>
                </div>
                <form wire:click="closeModal" class="modal-backdrop"><button type="button">close</button></form>
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
    (function() {
        let listenersAttached = false;
        function initCampaignDeleteListeners() {
            if (!listenersAttached && window.Livewire && window.Swal) {
                Livewire.on('show-campaign-delete-confirmation', (event) => {
                    let id = event.id ?? (event[0]?.id);
                    Swal.fire({
                        title: 'Anda Yakin?', text: "Program ini akan dihapus permanen!",
                        icon: 'warning', showCancelButton: true, confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33', confirmButtonText: 'Ya, Hapus!', cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed && id !== undefined) @this.call('delete', id);
                    });
                });
                Livewire.on('campaignDeleted', () => Swal.fire({ title: 'Berhasil!', text: 'Program berhasil dihapus.', icon: 'success', timer: 2000, showConfirmButton: false }));
                Livewire.on('deleteFailed', (e) => Swal.fire('Gagal!', e.message ?? (e[0]?.message ?? 'Gagal menghapus.'), 'error'));
                listenersAttached = true;
            }
        }
        document.addEventListener('livewire:navigated', () => { listenersAttached = false; initCampaignDeleteListeners(); });
        document.addEventListener('livewire:initialized', initCampaignDeleteListeners);

        if (typeof window.ckEditors === 'undefined') window.ckEditors = {};

        function createCkInstance(element, editorId, initialContent) {
            ClassicEditor.create(element, {}).then(editor => {
                window.ckEditors[editorId] = editor;
                editor.setData(initialContent || '');
                editor.model.document.on('change:data', () => {
                    const componentElement = element.closest('[wire\\:id]');
                    if (componentElement) {
                        const component = Livewire.find(componentElement.getAttribute('wire:id'));
                        if (component) component.set('description', editor.getData(), false);
                    }
                });
            }).catch(err => console.error('CKEditor create error:', err));
        }

        window.initCkEditor = function(editorId, initialContent) {
            const element = document.getElementById(editorId);
            if (!element || !window.ClassicEditor) return;
            if (window.ckEditors[editorId]) {
                window.ckEditors[editorId].destroy().then(() => {
                    window.ckEditors[editorId] = null;
                    createCkInstance(element, editorId, initialContent);
                }).catch(err => console.error(err));
            } else {
                createCkInstance(element, editorId, initialContent);
            }
        };

        window.destroyCkInstance = function(editorId) {
            if (window.ckEditors?.[editorId]) {
                window.ckEditors[editorId].destroy().then(() => { window.ckEditors[editorId] = null; }).catch(err => console.error(err));
            }
        };

        document.addEventListener('livewire:initialized', () => {
            Livewire.on('resetCkEditorContent', (event) => {
                if (window.ckEditors[event.editorId]) window.ckEditors[event.editorId].setData('');
            });
        });
    })();
    </script>
    @endpush
</div>
