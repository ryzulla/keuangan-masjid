<div> {{-- Mulai dengan div pembungkus biasa --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Buat Transaksi Baru
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    @if (session()->has('success'))
                        <div role="alert" class="alert alert-success shadow-lg mb-4">
                             <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                             <span>{{ session('success') }}</span>
                        </div>
                    @endif
                    @if (session()->has('error'))
                        <div role="alert" class="alert alert-error shadow-lg mb-4">
                             <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                             <span>{{ session('error') }}</span>
                        </div>
                    @endif
                    <form wire:submit="saveTransaction">
                        <div class="space-y-4">
                            <div class="form-control w-full">
                                <label class="label"><span class="label-text">Tipe Transaksi</span></label>
                                <select wire:model.live="type" class="select select-bordered w-full">
                                    <option value="debit">Pemasukan (Uang Masuk)</option>
                                    <option value="credit">Pengeluaran (Uang Keluar)</option>
                                </select>
                            </div>
                            <div class="form-control w-full">
                                <label class="label"><span class="label-text">Jumlah (Rp)</span></label>
                                <input type="number" step="any" wire:model="amount" class="input input-bordered w-full" placeholder="Contoh: 50000">
                                @error('amount') <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label> @enderror
                            </div>
                            <div class="form-control w-full">
                                <label class="label"><span class="label-text">Keterangan</span></label>
                                <input type="text" wire:model="description" class="input input-bordered w-full" placeholder="Contoh: Infaq Jumat 27 Okt 2025">
                                @error('description') <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label> @enderror
                            </div>
                            <div class="form-control w-full">
                                <label class="label"><span class="label-text">Simpan ke Akun/Kas</span></label>
                                <select wire:model="account_id" class="select select-bordered w-full">
                                    <option value="">-- Pilih Akun --</option>
                                    @foreach($accounts as $account)
                                        <option value="{{ $account->id }}">{{ $account->name }} (Rp {{ number_format($account->balance, 0, ',', '.') }})</option>
                                    @endforeach
                                </select>
                                @error('account_id') <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label> @enderror
                            </div>
                            <div class="form-control w-full">
                                <label class="label"><span class="label-text">Kategori Transaksi</span></label>
                                <select wire:model="category_id" class="select select-bordered w-full">
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id') <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label> @enderror
                            </div>
                            <div class="form-control w-full">
                                <label class="label"><span class="label-text">Tanggal Transaksi</span></label>
                                <input type="date" wire:model="transaction_date" class="input input-bordered w-full">
                                @error('transaction_date') <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label> @enderror
                            </div>
                        </div>
                        <div class="card-actions justify-end mt-6">
                            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                <span wire:loading.remove>Simpan Transaksi</span>
                                <span wire:loading class="loading loading-spinner loading-sm"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> {{-- Tutup div pembungkus biasa --}}
