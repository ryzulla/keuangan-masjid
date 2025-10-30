<div> {{-- Main Wrapper --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Manajemen Program/Kampanye
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Tombol Tambah Program --}}
            <div class="mb-4 text-right">
                <button wire:click="create()" class="btn btn-primary btn-sm">+ Tambah Program</button>
            </div>

            {{-- Notifikasi Global (Sukses & Error) --}}
            @if (session()->has('success') && !$isModalOpen)
                <div role="alert" class="alert alert-success shadow-lg mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span>{{ session('success') }}</span>
                    <button class="btn btn-sm btn-ghost" @click="$el.parentElement.remove()">✕</button>
                </div>
            @endif
            @if (session()->has('error') && !$isModalOpen || session()->has('render_error'))
                 <div role="alert" class="alert alert-error shadow-lg mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span>{{ session('error') ?? session('render_error') }}</span>
                     <button class="btn btn-sm btn-ghost" @click="$el.closest('.alert').remove()">✕</button>
                </div>
            @endif

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
                                    <th>Periode</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $campaignPaginator = ($campaigns instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator) ? $campaigns : null; @endphp
                                @if($campaignPaginator)
                                    @forelse($campaignPaginator->items() as $campaign)
                                        <tr class="hover" wire:key="campaign-row-{{ $campaign->id }}">
                                            {{-- Kolom Gambar --}}
                                            <td>
                                                @if($campaign->image)
                                                    <div class="avatar">
                                                        <div class="w-12 h-12 rounded">
                                                            <img src="{{ Storage::url($campaign->image) }}" alt="{{ $campaign->name }}" />
                                                        </div>
                                                    </div>
                                                @else
                                                    {{-- Placeholder --}}
                                                    <div class="avatar placeholder">
                                                      <div class="bg-neutral text-neutral-content rounded w-12 h-12 flex items-center justify-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" /></svg>
                                                      </div>
                                                    </div>
                                                @endif
                                            </td>
                                            {{-- Nama Program --}}
                                            <td>{{ $campaign->name }}</td>
                                            {{-- Target --}}
                                            <td>Rp {{ number_format($campaign->target_amount ?? 0, 0, ',', '.') }}</td>
                                            {{-- Terkumpul --}}
                                            <td class="font-semibold">
                                                Rp {{ number_format($campaign->transactions_sum_amount ?? 0, 0, ',', '.') }}
                                            </td>
                                            {{-- Progress --}}
                                            <td>
                                                @php
                                                    $target = (float)($campaign->target_amount ?? 0);
                                                    $raised = (float)($campaign->transactions_sum_amount ?? 0);
                                                    $progress = ($target > 0) ? min(100, ($raised / $target) * 100) : ($raised > 0 ? 100 : 0);
                                                @endphp
                                                <div class="tooltip tooltip-bottom w-full" data-tip="{{ number_format($progress, 1) }}% Tercapai">
                                                    <progress class="progress {{ $progress >= 100 ? 'progress-success' : 'progress-warning' }} w-full" value="{{ $progress }}" max="100"></progress>
                                                </div>
                                            </td>
                                            {{-- Status --}}
                                            <td>
                                                <span @class([
                                                    'badge badge-sm badge-outline',
                                                    'badge-success' => $campaign->status == 'active',
                                                    'badge-info' => $campaign->status == 'completed',
                                                    'badge-error' => $campaign->status == 'cancelled',
                                                ])>{{ $campaign->status }}</span>
                                            </td>
                                            {{-- Periode --}}
                                            <td class="whitespace-nowrap text-xs">
                                                {{ optional($campaign->start_date)->format('d/m/y') }} <br>
                                                {{ optional($campaign->end_date)->format('d/m/y') ?? '...' }}
                                            </td>
                                            {{-- Aksi --}}
                                            <td class="space-x-1 whitespace-nowrap">
                                                <button wire:click="edit({{ $campaign->id }})" class="btn btn-warning btn-xs">Edit</button>
                                                <button wire:click.prevent="confirmDelete({{ $campaign->id }})" class="btn btn-error btn-xs">Hapus</button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="8" class="text-center py-4">Belum ada data program/kampanye.</td></tr>
                                    @endforelse
                                @else
                                     <tr><td colspan="8" class="text-center py-4 text-error">Gagal memuat data program.</td></tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                     @if ($campaignPaginator)
                        <div class="mt-4">{{ $campaignPaginator->links() }}</div>
                     @endif
                </div>
            </div>

            {{-- Tambahkan Alpine context --}}
            <div
                class="modal {{ $isModalOpen ? 'modal-open' : '' }}"
                id="campaign-modal"
                {{-- Inisialisasi Alpine.js untuk modal ini --}}
                x-data="{ isModalOpen: @entangle('isModalOpen') }" {{-- Ikat isModalOpen ke Livewire --}}
                x-show="isModalOpen" {{-- Tampilkan modal berdasarkan state Alpine --}}
                x-on:keydown.escape.window="isModalOpen = false; @this.call('closeModal')" {{-- Tutup modal saat Esc --}}
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                style="display: none;" {{-- Sembunyikan default --}}
            >
                {{-- Konten Modal --}}
                <div class="modal-box w-11/12 max-w-3xl" @click.stop {{-- Hentikan event klik --}}
                     {{-- Awasi perubahan isModalOpen --}}
                     x-watch="isModalOpen, (value) => {
                         // Dapatkan ID editor yang benar
                         let selectedId = $wire.get('selected_id'); // Ambil ID dari Livewire
                         let editorId = 'campaignDescription' + (selectedId ? 'Edit' + selectedId : 'Create');

                         if (value) { // Jika modal BARU terbuka
                             console.log('Modal opened, initializing editor:', editorId);
                             // Tunggu DOM update, lalu inisialisasi editor
                             $nextTick(() => {
                                 initCkEditor(editorId, $wire.get('description'));
                             });
                         } else { // Jika modal BARU tertutup
                             console.log('Modal closed, destroying editor:', editorId);
                             // Hancurkan instance editor
                             destroyCkInstance(editorId);
                         }
                     }"
                 >
                    <h3 class="font-bold text-lg">{{ $selected_id ? 'Edit Program' : 'Buat Program Baru' }}</h3>

                     {{-- Display Validation & Modal Errors --}}
                    @if ($errors->any() || session()->has('modal_error'))
                        <div role="alert" class="{{ $errors->any() ? 'alert-warning' : 'alert-error' }} alert mt-4 text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                            <div>
                                <h3 class="font-bold">Oops!</h3>
                                @if(session()->has('modal_error'))
                                    <div class="text-xs">{{ session('modal_error') }}</div>
                                @else
                                    <ul class="list-disc pl-5 text-xs">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>
                    @endif


                    <form wire:submit="store" class="space-y-4 mt-4">

                        {{-- Nama Program --}}
                        <div class="form-control w-full">
                            <label class="label"><span class="label-text">Nama Program<span class="text-error">*</span></span></label>
                            <input type="text" wire:model="name" class="input input-bordered w-full">
                            @error('name') <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label> @enderror
                        </div>

                        {{-- Deskripsi (CKEditor) --}}
                        <div class="form-control w-full" wire:ignore> {{-- wire:ignore PENTING --}}
                            <label class="label"><span class="label-text">Deskripsi Lengkap (Opsional)</span></label>
                            {{-- Textarea ini akan digantikan oleh CKEditor --}}
                            <textarea
                                id="campaignDescription{{ $selected_id ? 'Edit'.$selected_id : 'Create' }}"
                                class="hidden" {{-- CKEditor akan menggantikan ini --}}
                            >{{-- Jangan gunakan wire:model di sini, sinkronisasi via JS --}}</textarea>
                            @error('description') <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label> @enderror
                        </div>

                         {{-- Upload Gambar --}}
                        <div class="form-control w-full">
                            <label class="label"><span class="label-text">Gambar Utama (Opsional - JPG, PNG, WEBP Maks 2MB)</span></label>
                            <input type="file" wire:model="image" id="campaignImage-{{ $this->getId() }}" class="file-input file-input-bordered file-input-sm w-full max-w-xs">
                            <div wire:loading wire:target="image" class="text-xs text-info mt-1">Mengunggah gambar...</div>
                            @error('image') <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label> @enderror

                            {{-- Preview Gambar --}}
                            <div class="mt-2">
                                @if ($image && !$errors->has('image'))
                                    <label class="label"><span class="label-text-alt">Preview Gambar Baru:</span></label>
                                    <img src="{{ $image->temporaryUrl() }}" class="h-24 w-auto rounded border p-1 object-cover">
                                @elseif ($existingImage)
                                    <label class="label"><span class="label-text-alt">Gambar Saat Ini:</span></label>
                                    <div class="inline-block relative group">
                                        <img src="{{ Storage::url($existingImage) }}" class="h-24 w-auto rounded border p-1 object-cover">
                                        <button type="button" wire:click="removeImage" wire:loading.attr="disabled"
                                                class="btn btn-xs btn-circle btn-error absolute -top-2 -right-2 opacity-0 group-hover:opacity-100 transition-opacity" title="Hapus Gambar Ini">
                                            ✕
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Target Donasi --}}
                        <div class="form-control w-full">
                            <label class="label"><span class="label-text">Target Donasi (Rp, Opsional)</span></label>
                            <input type="number" step="any" wire:model="target_amount" class="input input-bordered w-full" placeholder="0">
                            @error('target_amount') <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label> @enderror
                        </div>

                        {{-- Tanggal Mulai & Selesai --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                           <div class="form-control w-full">
                                <label class="label"><span class="label-text">Tanggal Mulai<span class="text-error">*</span></span></label>
                                <input type="date" wire:model="start_date" class="input input-bordered w-full">
                                @error('start_date') <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label> @enderror
                            </div>
                           <div class="form-control w-full">
                                <label class="label"><span class="label-text">Tanggal Selesai (Opsional)</span></label>
                                <input type="date" wire:model="end_date" class="input input-bordered w-full">
                                @error('end_date') <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label> @enderror
                            </div>
                        </div>

                        {{-- Status --}}
                        <div class="form-control w-full">
                            <label class="label"><span class="label-text">Status<span class="text-error">*</span></span></label>
                            <select wire:model="status" class="select select-bordered w-full">
                                <option value="active">Aktif</option>
                                <option value="completed">Selesai</option>
                                <option value="cancelled">Dibatalkan</option>
                            </select>
                            @error('status') <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label> @enderror
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
                 {{-- Modal Backdrop (Panggil closeModal() dari PHP) --}}
                <form wire:click="closeModal" class="modal-backdrop"><button type="button">close</button></form>
            </div> {{-- Akhir Modal --}}

        </div>
    </div>

    {{-- SweetAlert Script & CKEditor Script --}}
    @push('scripts')
        {{-- Script SweetAlert (Sama seperti sebelumnya) --}}
        <script>
            // IIFE untuk SweetAlert
            (function() {
                let listenersAttached = false;
                function initCampaignDeleteListeners() {
                    if (!listenersAttached && window.Livewire && window.Swal) {
                        Livewire.on('show-campaign-delete-confirmation', (event) => {
                            let componentId = event.id;
                            if (componentId === undefined && event[0] && event[0].id) { componentId = event[0].id;}
                            Swal.fire({
                                title: 'Anda Yakin?',
                                text: "Program ini akan dihapus permanen! Pastikan tidak ada donasi terkait.",
                                icon: 'warning', showCancelButton: true, confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33', confirmButtonText: 'Ya, Hapus!', cancelButtonText: 'Batal'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    if (componentId !== undefined) {
                                        @this.call('delete', componentId);
                                    } else {
                                        Swal.fire('Error', 'ID Program tidak valid.', 'error');
                                    }
                                }
                            });
                        });
                        Livewire.on('campaignDeleted', (event) => {
                             Swal.fire({ title: 'Berhasil!', text: 'Program berhasil dihapus.', icon: 'success', timer: 2000, showConfirmButton: false });
                        });
                        Livewire.on('deleteCampaignFailed', (event) => {
                             let message = event.message || (event[0] ? event[0].message : 'Gagal menghapus program.');
                             Swal.fire('Gagal!', message, 'error');
                        });
                        listenersAttached = true;
                    }
                }
                document.addEventListener('livewire:navigated', () => { listenersAttached = false; initCampaignDeleteListeners(); });
                document.addEventListener('livewire:initialized', () => { initCampaignDeleteListeners(); });
            })();

            // --- Script untuk CKEditor 5 ---
            (function() {
                // Objek global untuk menyimpan instance editor
                if (typeof window.ckEditors === 'undefined') {
                    window.ckEditors = {};
                }

                // Fungsi Inisialisasi/Re-inisialisasi Editor
                // @param editorId (string) - ID unik dari textarea
                // @param initialContent (string) - Konten awal untuk di-set
                function initCkEditor(editorId, initialContent) {
                    const element = document.getElementById(editorId);
                    // Pastikan elemen ada & CKEditor library sudah dimuat
                    if (!element || !window.ClassicEditor) {
                        console.warn(`CKEditor init failed: Element ${editorId} or ClassicEditor not found.`);
                        return;
                    }

                    // Hancurkan instance lama jika ada (penting untuk re-opening modal)
                    if (window.ckEditors[editorId]) {
                        window.ckEditors[editorId].destroy()
                            .then(() => {
                                window.ckEditors[editorId] = null;
                                // Buat ulang setelah dihancurkan
                                createCkInstance(element, editorId, initialContent);
                            })
                            .catch(error => console.error(`Error destroying CKEditor ${editorId}:`, error));
                    } else {
                        // Buat instance baru jika belum ada
                        createCkInstance(element, editorId, initialContent);
                    }
                }

                // Fungsi terpisah untuk membuat instance
                function createCkInstance(element, editorId, initialContent) {
                     ClassicEditor
                        .create(element, { /* Config CKEditor di sini jika perlu */ })
                        .then(editor => {
                            window.ckEditors[editorId] = editor; // Simpan instance
                            console.log(`CKEditor instance ${editorId} created.`);

                            // Set data awal
                            editor.setData(initialContent || '');

                            // Sinkronisasi dari Editor -> Livewire
                            editor.model.document.on('change:data', () => {
                                const componentElement = element.closest('[wire\\:id]');
                                if (componentElement) {
                                    const component = Livewire.find(componentElement.getAttribute('wire:id'));
                                    if (component) {
                                        // Update properti 'description' di Livewire
                                        component.set('description', editor.getData(), false); // false = jangan debounce
                                    }
                                }
                            });
                        })
                        .catch(error => {
                            console.error(`Error creating CKEditor ${editorId}:`, error);
                        });
                }

                // Fungsi untuk menghancurkan instance
                function destroyCkInstance(editorId) {
                    if (window.ckEditors && window.ckEditors[editorId]) {
                        window.ckEditors[editorId].destroy()
                            .then(() => {
                                console.log(`CKEditor instance ${editorId} destroyed.`);
                                window.ckEditors[editorId] = null;
                            })
                            .catch(error => console.error(`Error destroying CKEditor ${editorId}:`, error));
                    }
                }

                // --- Listener Livewire & Alpine ---

                // Dengarkan event dari PHP untuk mereset
                document.addEventListener('livewire:initialized', () => {
                     Livewire.on('resetCkEditorContent', (event) => {
                         if (window.ckEditors[event.editorId]) {
                             window.ckEditors[event.editorId].setData('');
                             console.log(`CKEditor content reset for: ${event.editorId}`);
                         }
                     });
                 });

                // Gunakan Alpine untuk memantau status modal
                document.addEventListener('alpine:init', () => {
                    // Cari semua modal di halaman (meskipun kita hanya punya satu)
                    document.querySelectorAll('[id^="campaign-modal"]').forEach(modalElement => {
                         // Dapatkan konteks data Alpine (harus ada x-data="{ isModalOpen: ... }" di modal)
                        if (modalElement._x_dataStack) {
                             let alpineData = modalElement._x_dataStack[0];

                             // Awasi perubahan 'isModalOpen'
                             Alpine.effect(() => {
                                let isOpen = alpineData.isModalOpen;
                                // Dapatkan ID editor yang benar
                                // Kita perlu cara untuk mendapatkan $selected_id di sini
                                // Cara mudah: Biarkan Livewire yang memberi tahu ID editor
                                // Tapi kita coba cara ini:
                                let editorEl = modalElement.querySelector('textarea[id^="campaignDescription"]');
                                if (!editorEl) return; // Belum siap

                                let editorId = editorEl.id;

                                if (isOpen) {
                                    // Saat modal terbuka, tunggu DOM siap lalu inisialisasi
                                    setTimeout(() => {
                                        // Ambil konten terbaru dari Livewire
                                        let component = Livewire.find(modalElement.closest('[wire\\:id]').getAttribute('wire:id'));
                                        let content = component.get('description');
                                        initCkEditor(editorId, content);
                                        console.log(`Alpine watch: Modal opened, initializing ${editorId}`);
                                    }, 100); // Beri delay agar modal render
                                } else {
                                    // Saat modal tertutup, hancurkan editor
                                    destroyCkInstance(editorId);
                                    console.log(`Alpine watch: Modal closed, destroying ${editorId}`);
                                }
                             });
                        }
                    });
                });

            })(); // Akhir IIFE CKEditor
        </script>
    @endpush

</div> {{-- End Main Wrapper --}}
