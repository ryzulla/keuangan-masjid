<div> {{-- Div pembungkus utama --}}
    <x-slot name="header">
         <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
             Transaksi List (Buku Besar)
         </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Tombol Tambah Transaksi --}}
            <div class="mb-4 text-right">
                <button wire:click="create()" class="btn btn-primary btn-sm">+ Tambah Transaksi</button>
            </div>

            {{-- Notifikasi Sukses --}}
            @if (session()->has('success') && !$isModalOpen)
                <div role="alert" class="alert alert-success shadow-lg mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span>{{ session('success') }}</span>
                    <button class="btn btn-sm btn-ghost" @click="$el.closest('.alert').remove()">✕</button>
                </div>
            @endif
            {{-- Notifikasi Error Render atau Operasi Lain (selain modal) --}}
            @if (session()->has('error') && !$isModalOpen || session()->has('render_error'))
                 <div role="alert" class="alert alert-error shadow-lg mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span>{{ session('error') ?? session('render_error') }}</span>
                     <button class="btn btn-sm btn-ghost" @click="$el.closest('.alert').remove()">✕</button>
                </div>
            @endif


            <div class="card bg-base-100 shadow-xl mb-6">
                <div class="card-body">
                    <h3 class="card-title text-base mb-4">Filter Transaksi</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                        {{-- Filter Tanggal Mulai --}}
                        <div class="form-control w-full">
                            <label class="label"><span class="label-text">Dari Tanggal</span></label>
                            <input type="date" wire:model.live.debounce.500ms="startDate" class="input input-bordered input-sm">
                        </div>
                        {{-- Filter Tanggal Akhir --}}
                        <div class="form-control w-full">
                            <label class="label"><span class="label-text">Sampai Tanggal</span></label>
                            <input type="date" wire:model.live.debounce.500ms="endDate" class="input input-bordered input-sm">
                        </div>
                        {{-- Filter Kategori --}}
                        <div class="form-control w-full">
                            <label class="label"><span class="label-text">Kategori</span></label>
                            {{-- Gunakan wire:model.live agar filter campaign bereaksi --}}
                            <select wire:model.live="selectedCategoryId" class="select select-bordered select-sm">
                                <option value="">Semua Kategori</option>
                                {{-- Loop dari $this->filterCategories (properti publik) --}}
                                @foreach($this->filterCategories as $category)
                                    <option value="{{ $category->id }}" wire:key="filter-cat-{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        {{-- Filter Kampanye (KONDISIONAL) --}}
                        {{-- Gunakan $showCampaignFilter (untuk filter), bukan $showCampaignDropdown (untuk modal) --}}
                        @if ($showCampaignFilter) {{-- Kondisi tetap sama --}}
                            <div class="form-control w-full">
                                <label class="label"><span class="label-text">Program/Kampanye</span></label>
                                <select wire:model.live="selectedCampaignId" class="select select-bordered select-sm">
                                    <option value="">Semua Program Terkait</option>
                                    @foreach($this->availableCampaigns as $campaign)
                                        <option value="{{ $campaign->id }}" wire:key="filter-camp-{{ $campaign->id }}">{{ $campaign->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                           <div class="hidden lg:block lg:min-h-[68px]"></div>
                        @endif
                    </div>
                     {{-- Tombol Ekspor --}}
                     <div class="card-actions justify-end mt-4">
                         {{-- Indikator loading untuk ekspor --}}
                         <span wire:loading wire:target="exportExcel, exportPdf" class="text-sm italic mr-2 self-center">
                            <span class="loading loading-spinner loading-xs"></span> Mengekspor...
                         </span>

                         <button wire:click="exportExcel" wire:loading.attr="disabled" wire:loading.class="loading btn-disabled" class="btn btn-sm btn-outline btn-success">
                             <span wire:loading.remove>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                                Excel
                             </span>
                         </button>
                         <button wire:click="exportPdf" wire:loading.attr="disabled" wire:loading.class="loading btn-disabled" class="btn btn-sm btn-outline btn-error">
                             <span wire:loading.remove>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                                PDF
                            </span>
                         </button>
                     </div>
                </div>
            </div>

            {{-- Pastikan variabel summary ada sebelum diakses --}}
            @isset($totalDebit, $totalCredit, $startDate, $endDate)
                <div class="stats shadow w-full stats-vertical lg:stats-horizontal mb-6">
                    @php
                        $safeStartDate = $startDate ? \Carbon\Carbon::parse($startDate)->format('d/m/y') : '-';
                        $safeEndDate = $endDate ? \Carbon\Carbon::parse($endDate)->format('d/m/y') : '-';
                        $netFlow = $totalDebit - $totalCredit;
                    @endphp
                    <div class="stat">
                        <div class="stat-title">Total Pemasukan (Debit)</div>
                        <div class="stat-value text-success">Rp {{ number_format($totalDebit, 0, ',', '.') }}</div>
                        <div class="stat-desc">Periode: {{ $safeStartDate }} - {{ $safeEndDate }}</div>
                    </div>
                    <div class="stat">
                        <div class="stat-title">Total Pengeluaran (Kredit)</div>
                        <div class="stat-value text-error">Rp {{ number_format($totalCredit, 0, ',', '.') }}</div>
                        <div class="stat-desc">Periode: {{ $safeStartDate }} - {{ $safeEndDate }}</div>
                    </div>
                    <div class="stat">
                        <div class="stat-title">Selisih (Net Flow)</div>
                        <div class="stat-value {{ $netFlow >= 0 ? 'text-info' : 'text-error' }}">
                            Rp {{ number_format($netFlow, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
            @endisset


            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <div class="overflow-x-auto">
                        <table class="table table-sm table-zebra w-full">
                            <thead>
                                <tr>
                                    <th class="whitespace-nowrap">Tanggal</th>
                                    <th>Keterangan</th>
                                    <th>Kategori</th>
                                    <th>Akun/Kas</th>
                                    <th>Program</th> {{-- <-- Kolom Baru --}}
                                    <th>Donatur</th> {{-- <-- Kolom Baru --}}
                                    <th>Bukti</th>
                                    <th>User</th>
                                    <th class="text-right">Debit (+)</th>
                                    <th class="text-right">Kredit (-)</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $transactionPaginator = ($transactions instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator) ? $transactions : null;
                                @endphp
                                @if($transactionPaginator)
                                    @forelse($transactionPaginator->items() as $tx)
                                        <tr class="hover" wire:key="tx-{{ $tx->id }}">
                                            <td class="whitespace-nowrap">{{ optional($tx->transaction_date)->format('d/m/Y') }}</td>
                                            <td>{{ $tx->description }}</td>
                                            <td><div class="badge badge-ghost badge-sm">{{ optional($tx->category)->name ?? '-' }}</div></td>
                                            <td><div class="badge badge-outline badge-sm">{{ optional($tx->account)->name ?? '-' }}</div></td>
                                            {{-- Kolom Program --}}
                                            <td><div class="badge badge-info badge-sm badge-outline">{{ optional($tx->campaign)->name ?? '-' }}</div></td>
                                            {{-- Kolom Donatur --}}
                                            <td>{{ optional($tx->donation)->donor_name ?? '-' }}</td>
                                            {{-- Kolom Bukti --}}
                                            <td>
                                                @if ($tx->attachment)
                                                    <a href="{{ Storage::url($tx->attachment) }}" target="_blank" class="link link-info text-xs">Lihat</a>
                                                @else - @endif
                                            </td>
                                            <td>{{ optional($tx->user)->name ?? '-' }}</td>
                                            {{-- Kolom Debit/Kredit --}}
                                            @if($tx->type == 'debit')
                                                <td class="text-right font-mono text-success">Rp {{ number_format($tx->amount, 0, ',', '.') }}</td>
                                                <td class="text-right font-mono">-</td>
                                            @else
                                                <td class="text-right font-mono">-</td>
                                                <td class="text-right font-mono text-error">Rp {{ number_format($tx->amount, 0, ',', '.') }}</td>
                                            @endif
                                            {{-- Kolom Aksi --}}
                                            <td class="space-x-1 whitespace-nowrap">
                                                <button wire:click="edit({{ $tx->id }})" class="btn btn-warning btn-xs">Edit</button>
                                                <button wire:click.prevent="confirmDelete({{ $tx->id }})" class="btn btn-error btn-xs">Hapus</button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="11" class="text-center py-4">Belum ada transaksi pada periode/filter ini.</td></tr> {{-- Sesuaikan colspan --}}
                                    @endforelse
                                @else
                                     <tr><td colspan="11" class="text-center py-4 text-error">Gagal memuat data transaksi. Periksa log.</td></tr> {{-- Sesuaikan colspan --}}
                                @endif
                            </tbody>
                        </table>
                    </div>
                     {{-- Tampilkan Link Paginasi jika $transactions adalah Paginator --}}
                    @if ($transactionPaginator)
                         <div class="mt-4">{{ $transactionPaginator->links() }}</div>
                    @endif
                </div>
            </div>


            <div class="modal {{ $isModalOpen ? 'modal-open' : '' }}" id="transaction-modal"> {{-- Beri ID unik --}}
                <div class="modal-box w-11/12 max-w-2xl">
                    <h3 class="font-bold text-lg">{{ $selected_id ? 'Edit Transaksi' : 'Tambah Transaksi Baru' }}</h3>

                    {{-- Tampilkan error validasi Laravel di dalam modal --}}
                    @if ($errors->any())
                        <div role="alert" class="alert alert-warning mt-4 text-sm">
                            {{-- ... (Error SVG) ... --}}
                            <div>
                                <h3 class="font-bold">Oops! Ada kesalahan input:</h3>
                                <ul class="list-disc pl-5">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                     {{-- Tampilkan error exception custom di dalam modal --}}
                    @if (session()->has('modal_error'))
                        <div role="alert" class="alert alert-error shadow-lg mt-4">
                             {{-- ... (Error SVG) ... --}}
                            <span>{{ session('modal_error') }}</span>
                        </div>
                    @endif

                    <form wire:submit="store" class="space-y-4 mt-4">
                        {{-- Field Tipe Transaksi --}}
                        <div class="form-control w-full">
                            <label class="label"><span class="label-text">Tipe Transaksi</span></label>
                            <select wire:model.live="type" class="select select-bordered w-full">
                                <option value="debit">Pemasukan (Uang Masuk)</option>
                                <option value="credit">Pengeluaran (Uang Keluar)</option>
                            </select>
                        </div>
                        {{-- Field Jumlah --}}
                        <div class="form-control w-full">
                            <label class="label"><span class="label-text">Jumlah (Rp)</span></label>
                            <input type="number" step="any" wire:model="amount" class="input input-bordered w-full" placeholder="Contoh: 50000">
                        </div>
                         {{-- Field Keterangan --}}
                        <div class="form-control w-full">
                            <label class="label"><span class="label-text">Keterangan</span></label>
                            <input type="text" wire:model="description" class="input input-bordered w-full" placeholder="Contoh: Infaq Jumat 27 Okt 2025">
                        </div>
                         {{-- Field Akun/Kas --}}
                        <div class="form-control w-full">
                            <label class="label"><span class="label-text">Akun/Kas</span></label>
                            <select wire:model="account_id" class="select select-bordered w-full">
                                <option value="">-- Pilih Akun --</option>
                                {{-- Akses properti publik via $this --}}
                                @foreach($this->accounts as $account)
                                    <option value="{{ $account->id }}" wire:key="modal-account-{{ $account->id }}">
                                        {{ $account->name }} (Saldo: Rp {{ number_format($account->balance, 0, ',', '.') }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                         {{-- Field Kategori Transaksi --}}
                        <div class="form-control w-full">
                            <label class="label"><span class="label-text">Kategori Transaksi</span></label>
                            {{-- Indikator loading saat kategori berubah --}}
                            <div wire:loading wire:target="type, category_id" class="text-xs text-gray-500">Memuat kategori...</div>
                            {{-- Nonaktifkan select saat loading --}}
                            <select wire:model.live="category_id" class="select select-bordered w-full" wire:loading.attr="disabled" wire:target="type">
                                <option value="">-- Pilih Kategori --</option>
                                {{-- Akses properti publik via $this --}}
                                @foreach($this->categories as $category)
                                    <option value="{{ $category->id }}" wire:key="modal-category-{{ $category->id }}">
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                             @error('category_id') <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label> @enderror
                        </div>

                         {{-- Field Program/Kampanye (KONDISIONAL di Modal) --}}
                         {{-- Gunakan $showCampaignDropdown (untuk modal) --}}
                        @if ($showCampaignDropdown)
                            <div class="form-control w-full bg-blue-50 dark:bg-gray-700 p-3 rounded-md border border-blue-200 dark:border-gray-600">
                                <label class="label"><span class="label-text">Untuk Program/Kampanye</span></label>
                                <select wire:model="campaign_id" class="select select-bordered w-full">
                                    <option value="">-- Pilih Program --</option> {{-- Buat default wajib jika perlu --}}
                                    {{-- Gunakan $this->availableCampaigns --}}
                                    @foreach($this->availableCampaigns as $campaign)
                                        <option value="{{ $campaign->id }}" wire:key="modal-campaign-{{ $campaign->id }}">
                                            {{ $campaign->name }}
                                        </option>
                                    @endforeach
                                </select>
                                {{-- Tampilkan error validasi untuk campaign_id jika ada --}}
                                @error('campaign_id') <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label> @enderror
                            </div>
                        @endif

                        {{-- === INPUT NAMA DONATUR (KONDISIONAL) === --}}
                        @if ($type == 'debit')
                            <div class="form-control w-full">
                                <label class="label"><span class="label-text">Nama Donatur (Opsional)</span></label>
                                <input type="text" wire:model="donor_name" class="input input-bordered w-full" placeholder="Nama pemberi donasi">
                                @error('donor_name') <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label> @enderror
                            </div>
                        @endif
                        {{-- === AKHIR INPUT NAMA DONATUR === --}}

                         {{-- Field Tanggal Transaksi --}}
                        <div class="form-control w-full">
                            <label class="label"><span class="label-text">Tanggal Transaksi</span></label>
                            <input type="date" wire:model="transaction_date" class="input input-bordered w-full">
                             @error('transaction_date') <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label> @enderror
                        </div>

                        {{-- Input File Attachment --}}
                        <div class="form-control w-full">
                            <label class="label"><span class="label-text">Bukti Transaksi (Opsional - PDF, JPG, PNG, WEBP Maks 2MB)</span></label>
                            <input type="file" wire:model="attachmentFile" id="attachmentFile-{{ $this->getId() }}" class="file-input file-input-bordered file-input-sm w-full max-w-xs"> {{-- Batasi lebar --}}
                            {{-- Loading Indicator --}}
                            <div wire:loading wire:target="attachmentFile" class="text-xs text-info mt-1">Mengunggah...</div>
                            {{-- Error File --}}
                            @error('attachmentFile') <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label> @enderror

                            {{-- Preview/Hapus File Lama (Saat Edit) --}}
                            @if ($existingAttachment && !$attachmentFile) {{-- Hanya tampilkan jika tidak ada file baru dipilih --}}
                                <div class="mt-2 text-sm">
                                    Bukti saat ini:
                                    <a href="{{ Storage::url($existingAttachment) }}" target="_blank" class="link link-primary ml-2 break-all">{{ basename($existingAttachment) }}</a>
                                    {{-- Tombol Hapus Attachment Lama --}}
                                    <button type="button" wire:click="removeAttachment" wire:loading.attr="disabled" class="btn btn-xs btn-ghost text-error ml-2" title="Hapus bukti saat ini">
                                       ✕ Hapus
                                    </button>
                                </div>
                            {{-- Preview File Baru (jika ada & valid) --}}
                            @elseif ($attachmentFile && !$errors->has('attachmentFile'))
                                <div class="mt-2 text-sm text-success">
                                    File baru: {{ $attachmentFile->getClientOriginalName() }} ({{ round($attachmentFile->getSize() / 1024) }} KB)
                                    {{-- Opsi Batal (Reset Input File) --}}
                                     <button type="button" wire:click="$set('attachmentFile', null)" class="btn btn-xs btn-ghost text-warning ml-2" title="Batal pilih file">
                                       ✕ Batal
                                    </button>
                                </div>
                            @endif
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
                 {{-- Klik backdrop untuk menutup modal --}}
                <form wire:click="closeModal" class="modal-backdrop"><button type="button">close</button></form>
            </div>

        </div>
    </div>

    {{-- Script untuk SweetAlert --}}
    @push('scripts')
    <script>
        // Tandai bahwa listener belum terpasang
        if (typeof window.sweetAlertListenersAttached === 'undefined') {
            window.sweetAlertListenersAttached = false;
        }

        // Fungsi untuk memasang listener
        function initSweetAlertListeners() {
            // Hanya pasang jika belum ada
            if (!window.sweetAlertListenersAttached) {
                // Listener untuk menampilkan konfirmasi hapus
                Livewire.on('show-delete-confirmation', (event) => {
                    let componentId = event.id; // Ambil ID dari event Livewire 3
                    if (componentId === undefined && event[0] && event[0].id) { // Fallback
                         componentId = event[0].id;
                    }

                    if (!window.Swal) { console.error('Swal is not defined!'); return; }
                    Swal.fire({
                        title: 'Anda Yakin?',
                        text: "Transaksi ini akan dihapus permanen! Saldo akun akan disesuaikan.",
                        icon: 'warning', showCancelButton: true, confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33', confirmButtonText: 'Ya, Hapus!', cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            if (componentId !== undefined) {
                                @this.call('delete', componentId);
                            } else {
                                console.error('Event ID is undefined!', event);
                                Swal.fire('Error', 'ID Transaksi tidak valid.', 'error');
                            }
                        }
                    });
                });

                // Listener untuk notifikasi sukses hapus
                Livewire.on('transactionDeleted', (event) => {
                     Swal.fire({
                        title: 'Berhasil!', text: 'Transaksi berhasil dihapus.', icon: 'success',
                        timer: 2000, showConfirmButton: false
                     });
                });

                // Listener untuk notifikasi gagal hapus
                 Livewire.on('deleteFailed', (event) => {
                     let message = 'Gagal menghapus transaksi.'; // Default
                      // Cek format event Livewire 3
                     if (event.message) { message = event.message;}
                      // Fallback jika message ada di dalam array
                     else if (event[0] && event[0].message) { message = event[0].message; }
                     Swal.fire('Gagal!', message, 'error');
                });

                 window.sweetAlertListenersAttached = true; // Tandai
            }
        }

        // Pasang listener saat Livewire siap dan setelah navigasi
        document.addEventListener('livewire:navigated', () => {
            window.sweetAlertListenersAttached = false; // Reset flag
            initSweetAlertListeners();
        });
        document.addEventListener('livewire:initialized', () => {
             initSweetAlertListeners();
        });

    </script>
    @endpush

</div> {{-- Tutup div utama komponen --}}
