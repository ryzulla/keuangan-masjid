<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Manajemen Pengguna
            </h2>
            <button wire:click="create()" class="btn btn-primary btn-sm">+ Tambah Pengguna</button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session()->has('success'))
                <div role="alert" class="alert alert-success shadow-lg mb-4">...<span>{{ session('success') }}</span></div>
            @endif
            @if (session()->has('error'))
                 <div role="alert" class="alert alert-error shadow-lg mb-4">...<span>{{ session('error') }}</span></div>
            @endif

            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <div class="overflow-x-auto">
                        <table class="table w-full">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr class="hover">
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td><span class="badge badge-sm badge-outline">{{ $user->role }}</span></td>
                                        <td class="space-x-1">
                                            <button wire:click="edit({{ $user->id }})" class="btn btn-warning btn-xs">Edit</button>
                                            @if($user->id != auth()->id())
                                                <button wire:click="delete({{ $user->id }})" wire:confirm="Anda yakin?" class="btn btn-error btn-xs">Hapus</button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center">Belum ada data.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">{{ $users->links() }}</div>
                </div>
            </div>

            <div class="modal {{ $isModalOpen ? 'modal-open' : '' }}">
                <div class="modal-box w-11/12 max-w-lg">
                    <h3 class="font-bold text-lg">{{ $selected_id ? 'Edit Pengguna' : 'Buat Pengguna Baru' }}</h3>
                    <form wire:submit="store" class="space-y-4 mt-4">

                        <div class="form-control w-full">
                            <label class="label"><span class="label-text">Nama Lengkap</span></label>
                            <input type="text" wire:model="name" class="input input-bordered w-full">
                            @error('name') <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label> @enderror
                        </div>

                        <div class="form-control w-full">
                            <label class="label"><span class="label-text">Email</span></label>
                            <input type="email" wire:model="email" class="input input-bordered w-full">
                            @error('email') <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label> @enderror
                        </div>

                        <div class="form-control w-full">
                            <label class="label"><span class="label-text">Role</span></label>
                            <select wire:model="role" class="select select-bordered w-full">
                                <option value="bendahara">Bendahara</option>
                                <option value="ketua_dkm">Ketua DKM</option>
                                <option value="admin">Admin</option>
                            </select>
                            @error('role') <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label> @enderror
                        </div>

                        <div class="form-control w-full">
                            <label class="label"><span class="label-text">Password</span></label>
                            <input type="password" wire:model="password" class="input input-bordered w-full" placeholder="{{ $selected_id ? 'Kosongkan jika tidak ingin diubah' : '' }}">
                            @error('password') <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label> @enderror
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
