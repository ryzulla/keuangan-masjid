<?php
namespace App\Livewire\IPL;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\IplTariffType;
use App\Models\Account;
use App\Models\Setting;
use Illuminate\Support\Facades\Log;

#[Layout('layouts.app')]
class TariffSettings extends Component
{
    public bool $isModalOpen = false;
    public ?int $editingId = null;
    public string $name = '';
    public string $description = '';
    public string $defaultAmount = '';
    public bool $isActive = true;
    public int $sortOrder = 0;

    public array $tariffAccounts = [];
    public $donationAccountPerumahan = null;
    public $donationAccountDkm = null;

    public function mount(): void
    {
        foreach (IplTariffType::all() as $type) {
            $this->tariffAccounts[$type->id] = $type->default_account_id;
        }
        $this->donationAccountPerumahan = Setting::getInt('donation_account_perumahan');
        $this->donationAccountDkm = Setting::getInt('donation_account_dkm');
    }

    protected function rules(): array
    {
        return [
            'name'          => 'required|string|max:100',
            'description'   => 'nullable|string|max:255',
            'defaultAmount' => 'required|numeric|min:0',
            'isActive'      => 'boolean',
            'sortOrder'     => 'integer|min:0',
        ];
    }

    public function openCreate(): void
    {
        $this->reset(['editingId', 'name', 'description', 'defaultAmount', 'sortOrder']);
        $this->isActive = true;
        $this->sortOrder = (IplTariffType::max('sort_order') ?? 0) + 1;
        $this->resetErrorBag();
        $this->isModalOpen = true;
    }

    public function openEdit(int $id): void
    {
        $type = IplTariffType::findOrFail($id);
        $this->editingId     = $id;
        $this->name          = $type->name;
        $this->description   = $type->description ?? '';
        $this->defaultAmount = (string) $type->default_amount;
        $this->isActive      = $type->is_active;
        $this->sortOrder     = $type->sort_order;
        $this->resetErrorBag();
        $this->isModalOpen = true;
    }

    public function save(): void
    {
        $this->validate();
        try {
            $data = [
                'name'           => $this->name,
                'description'    => $this->description ?: null,
                'default_amount' => $this->defaultAmount,
                'is_active'      => $this->isActive,
                'sort_order'     => $this->sortOrder,
            ];

            if ($this->editingId) {
                IplTariffType::findOrFail($this->editingId)->update($data);
                session()->flash('success', 'Tarif "' . $this->name . '" berhasil diperbarui.');
            } else {
                IplTariffType::create($data);
                session()->flash('success', 'Tarif "' . $this->name . '" berhasil ditambahkan.');
            }
            $this->closeModal();
        } catch (\Exception $e) {
            Log::error('TariffSettings save error: ' . $e->getMessage());
            session()->flash('modal_error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    public function confirmDelete(int $id): void
    {
        $this->dispatch('show-delete-confirmation', id: $id);
    }

    public function delete(int $id): void
    {
        $type = IplTariffType::findOrFail($id);
        if ($type->billing_key) {
            session()->flash('error', 'Tarif bawaan sistem tidak dapat dihapus, hanya bisa dinonaktifkan.');
            return;
        }
        try {
            $type->delete();
            session()->flash('success', 'Tarif berhasil dihapus.');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menghapus tarif: ' . $e->getMessage());
        }
    }

    public function toggleActive(int $id): void
    {
        $type = IplTariffType::findOrFail($id);
        $type->update(['is_active' => !$type->is_active]);
        session()->flash('success', 'Status tarif "' . $type->name . '" diubah.');
    }

    public function closeModal(): void
    {
        $this->isModalOpen = false;
        $this->reset(['editingId', 'name', 'description', 'defaultAmount', 'isActive', 'sortOrder']);
        $this->resetErrorBag();
        session()->forget('modal_error');
    }

    public function saveAccounts(): void
    {
        try {
            foreach ($this->tariffAccounts as $id => $accountId) {
                IplTariffType::whereKey($id)->update([
                    'default_account_id' => $accountId ?: null,
                ]);
            }
            Setting::set('donation_account_perumahan', $this->donationAccountPerumahan ?: null);
            Setting::set('donation_account_dkm', $this->donationAccountDkm ?: null);

            session()->flash('success', 'Pengaturan akun tujuan pembayaran berhasil disimpan.');
        } catch (\Exception $e) {
            Log::error('TariffSettings saveAccounts error: ' . $e->getMessage());
            session()->flash('error', 'Gagal menyimpan pengaturan akun: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.ipl.tariff-settings', [
            'systemTypes'        => IplTariffType::whereNotNull('billing_key')->orderBy('sort_order')->orderBy('id')->get(),
            'extraTypes'         => IplTariffType::whereNull('billing_key')->orderBy('sort_order')->orderBy('id')->get(),
            'perumahanAccounts'  => Account::where('organization_type', 'perumahan')->orderBy('name')->get(),
            'dkmAccounts'        => Account::where('organization_type', 'dkm')->orderBy('name')->get(),
        ]);
    }
}
