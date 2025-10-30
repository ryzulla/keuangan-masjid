<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Manajemen Kategori
            </h2>
            <button wire:click="create()" class="btn btn-primary btn-sm">+ Tambah Kategori</button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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

            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <div class="overflow-x-auto">
                        <table class="table w-full">
                            <thead>
                                <tr>
                                    <th>Nama Kategori</th>
                                    <th>Tipe</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories as $category)
                                    <tr class="hover">
                                        <td>{{ $category->name }}</td>
                                        <td>
                                            @if($category->type == 'income')
                                                <span class="badge badge-success badge-outline">Pemasukan</span>
                                            @else
                                                <span class="badge badge-error badge-outline">Pengeluaran</span>
                                            @endif
                                        </td>
                                        <td class="space-x-1">
                                            <button wire:click="edit({{ $category->id }})" class="btn btn-warning btn-xs">Edit</button>
                                            <button wire:click="delete({{ $category->id }})" wire:confirm="Anda yakin? Menghapus kategori tidak bisa dibatalkan." class="btn btn-error btn-xs">Hapus</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-center">Belum ada data.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">{{ $categories->links() }}</div>
                </div>
            </div>

            <div class="modal {{ $isModalOpen ? 'modal-open' : '' }}">
                <div class="modal-box w-11/12 max-w-lg">
                    <h3 class="font-bold text-lg">{{ $selected_id ? 'Edit Kategori' : 'Buat Kategori Baru' }}</h3>
                    <form wire:submit="store" class="space-y-4 mt-4">

                        <div class="form-control w-full">
                            <label class="label"><span class="label-text">Nama Kategori</span></label>
                            <input type="text" wire:model="name" class="input input-bordered w-full">
                            @error('name') <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label> @enderror
                        </div>

                        <div class="form-control w-full">
                            <label class="label"><span class="label-text">Tipe Kategori</span></label>
                            <select wire:model="type" class="select select-bordered w-full">
                                <option value="income">Pemasukan (Income)</option>
                                <option value="expense">Pengeluaran (Expense)</option>
                            </select>
                            @error('type') <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label> @enderror
                        </div>

                        <div class="modal-action">
                            <button type="button" wire:click="closeModal()" class="btn btn-ghost">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
                <form wire:click="closeModal" class="modal-backdrop"><button type="button">close</button></form>
            </div>

        </div>
    </div>
</div>
