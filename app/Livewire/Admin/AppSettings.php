<?php
namespace App\Livewire\Admin;

use App\Models\Setting;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class AppSettings extends Component
{
    public string $appName = '';
    public string $appSubtitle = '';
    public string $homeTitle = '';
    public string $homeTagline = '';
    public bool $modulePerumahan = true;
    public bool $moduleDkm = true;

    public function mount(): void
    {
        // Halaman ini hanya untuk superadmin.
        abort_unless(auth()->user()?->role === 'super_admin', 403);

        $this->appName        = Setting::get('app_name', config('app.name', 'Sistem Perumahan'));
        $this->appSubtitle    = Setting::appSubtitle();
        $this->homeTitle      = Setting::get('home_title', '');
        $this->homeTagline    = Setting::get('home_tagline', '');
        $this->modulePerumahan = Setting::moduleEnabled('perumahan');
        $this->moduleDkm       = Setting::moduleEnabled('dkm');
    }

    protected function rules(): array
    {
        return [
            'appName'     => 'required|string|max:100',
            'appSubtitle' => 'nullable|string|max:150',
            'homeTitle'   => 'nullable|string|max:100',
            'homeTagline' => 'nullable|string|max:300',
        ];
    }

    public function save(): void
    {
        abort_unless(auth()->user()?->role === 'super_admin', 403);
        $this->validate();

        Setting::set('app_name', trim($this->appName));
        Setting::set('app_subtitle', trim($this->appSubtitle));
        Setting::set('home_title', trim($this->homeTitle));
        Setting::set('home_tagline', trim($this->homeTagline));
        Setting::set('module_perumahan_enabled', $this->modulePerumahan ? '1' : '0');
        Setting::set('module_dkm_enabled', $this->moduleDkm ? '1' : '0');

        session()->flash('success', 'Pengaturan aplikasi berhasil disimpan.');
    }

    public function render()
    {
        return view('livewire.admin.app-settings');
    }
}
