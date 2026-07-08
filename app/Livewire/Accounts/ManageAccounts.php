<?php
namespace App\Livewire\Accounts;
use Livewire\Component;
use App\Models\Account;
use Livewire\WithPagination;
use Livewire\Attributes\Layout; // <-- Penting untuk layout

#[Layout('layouts.app')] // <-- Menentukan layout utama
class ManageAccounts extends Component
{
    use WithPagination;
    public $name, $description, $balance = 0, $organization_type = 'perumahan';
    public $selected_id;
    public $isModalOpen = false;

    protected $rules = [
        'name' => 'required|string|min:3',
        'balance' => 'required|numeric|min:0',
        'description' => 'nullable|string',
        'organization_type' => 'required|in:perumahan,dkm,umum',
    ];

    public function render()
    {
        return view('livewire.accounts.manage-accounts', [
            'accounts' => Account::latest()->paginate(10)
        ]);
    }

    public function create()
    {
        $this->resetForm();
        $this->isModalOpen = true;
    }

    public function edit($id)
    {
        $account = Account::findOrFail($id);
        $this->selected_id = $id;
        $this->name = $account->name;
        $this->description = $account->description;
        $this->balance = $account->balance;
        $this->organization_type = $account->organization_type ?? 'perumahan';
        $this->isModalOpen = true;
    }

    public function store()
    {
        $this->validate();
        $data = [
            'name' => $this->name,
            'description' => $this->description,
            'organization_type' => $this->organization_type,
        ];
        if(!$this->selected_id) {
            $data['balance'] = $this->balance;
        }

        Account::updateOrCreate(['id' => $this->selected_id], $data);
        session()->flash('success', $this->selected_id ? 'Akun berhasil diperbarui.' : 'Akun berhasil dibuat.');
        $this->closeModal();
    }

    public function delete($id)
    {
        $account = Account::withCount('transactions')->find($id);
        if ($account->transactions_count > 0) {
            session()->flash('error', 'Gagal! Akun ini memiliki transaksi terkait.');
            return;
        }
        $account->delete();
        session()->flash('success', 'Akun berhasil dihapus.');
    }

    public function closeModal() { $this->isModalOpen = false; $this->resetForm(); }
    private function resetForm() { $this->reset(['name', 'description', 'balance', 'selected_id', 'organization_type']); $this->organization_type = 'perumahan'; }
}
