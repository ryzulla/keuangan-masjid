<?php
namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\RolePermission;
use Illuminate\Support\Facades\Cache;

#[Layout('layouts.app')]
class RoleAccessSettings extends Component
{
    public array $matrix = [];

    const ROLES = [
        'admin', 'bendahara', 'ketua_dkm', 'dkm', 'perumahan', 'pengurus_rt',
    ];

    const ROLE_LABELS = [
        'admin'       => 'Admin',
        'bendahara'   => 'Bendahara',
        'ketua_dkm'   => 'Ketua DKM',
        'dkm'         => 'DKM',
        'perumahan'   => 'Perumahan',
        'pengurus_rt' => 'Pengurus RT',
    ];

    const GATES = [
        'manage-admin'        => ['label' => 'Manajemen Pengguna',            'group' => 'Administrasi',   'icon' => 'users'],
        'manage-transactions' => ['label' => 'Master Data (Akun & Kategori)', 'group' => 'Administrasi',   'icon' => 'cog'],
        'view-reports'        => ['label' => 'Laporan Keuangan',              'group' => 'Administrasi',   'icon' => 'chart'],
        'manage-dkm'          => ['label' => 'Transaksi DKM',                 'group' => 'DKM Masjid',     'icon' => 'book'],
        'manage-programs'     => ['label' => 'Program (DKM & Perumahan)',     'group' => 'DKM Masjid',     'icon' => 'star'],
        'manage-residents'    => ['label' => 'Data Penghuni & Blok Rumah',    'group' => 'Perumahan',      'icon' => 'home'],
        'manage-ipl'          => ['label' => 'IPL & Pengaturan Tarif',        'group' => 'Perumahan',      'icon' => 'receipt'],
        'manage-perumahan'    => ['label' => 'Transaksi Perumahan',           'group' => 'Perumahan',      'icon' => 'cash'],
    ];

    public function mount(): void
    {
        $existing = RolePermission::all();

        foreach (self::ROLES as $role) {
            foreach (array_keys(self::GATES) as $gate) {
                $this->matrix[$role][$gate] = $existing
                    ->where('role', $role)->where('gate', $gate)->isNotEmpty();
            }
        }
    }

    public function save(): void
    {
        // Hapus semua permission yang bisa diedit (bukan super_admin)
        RolePermission::whereIn('role', self::ROLES)->delete();

        foreach ($this->matrix as $role => $gates) {
            if (!in_array($role, self::ROLES)) continue;
            foreach ($gates as $gate => $allowed) {
                if ($allowed) {
                    RolePermission::create(['role' => $role, 'gate' => $gate]);
                }
            }
        }

        // Clear cache semua gate
        foreach (array_keys(self::GATES) as $gate) {
            Cache::forget("gate_roles_{$gate}");
        }

        session()->flash('success', 'Pengaturan akses role berhasil disimpan.');
    }

    public function resetToDefault(): void
    {
        $defaults = [
            'admin'       => ['manage-admin', 'manage-dkm', 'manage-perumahan', 'manage-programs', 'manage-transactions', 'view-reports', 'manage-residents', 'manage-ipl'],
            'bendahara'   => ['manage-dkm', 'manage-programs', 'manage-transactions', 'view-reports', 'manage-ipl'],
            'ketua_dkm'   => ['view-reports'],
            'dkm'         => ['manage-dkm', 'manage-programs'],
            'perumahan'   => ['manage-perumahan', 'manage-programs', 'manage-residents', 'manage-ipl'],
            'pengurus_rt' => ['manage-perumahan', 'manage-programs', 'manage-transactions', 'view-reports', 'manage-residents', 'manage-ipl'],
        ];

        foreach (self::ROLES as $role) {
            foreach (array_keys(self::GATES) as $gate) {
                $this->matrix[$role][$gate] = in_array($gate, $defaults[$role] ?? []);
            }
        }

        session()->flash('info', 'Matrix dikembalikan ke default. Klik Simpan untuk menyimpan perubahan.');
    }

    public function render()
    {
        // Hitung summary per role
        $summary = [];
        foreach (self::ROLES as $role) {
            $summary[$role] = collect($this->matrix[$role] ?? [])->filter()->count();
        }

        return view('livewire.admin.role-access-settings', [
            'gates'      => self::GATES,
            'roles'      => self::ROLES,
            'roleLabels' => self::ROLE_LABELS,
            'summary'    => $summary,
        ]);
    }
}
