<?php
namespace App\Livewire\Categories;

use Livewire\Component;
use App\Models\Category;
use Livewire\WithPagination;
use Livewire\Attributes\Layout; // <-- Penting untuk layout

#[Layout('layouts.app')] // <-- Menentukan layout utama
class ManageCategories extends Component
{
    use WithPagination;

    // Properti Form
    public $name;
    public $type = 'income';
    public $organization_type = 'perumahan';

    public string $fund_type = '';
    public $selected_id;
    public $isModalOpen = false;

    protected $rules = [
        'name' => 'required|string|min:3|max:255',
        'type' => 'required|in:income,expense',
        'organization_type' => 'required|in:perumahan,dkm,umum',
        'fund_type' => 'nullable|in:zakat,infaq,sedekah,wakaf,umum',
    ];

    public function render()
    {
        return view('livewire.categories.manage-categories', [
            'categories' => Category::latest()->paginate(10)
        ]);
    }

    public function create()
    {
        $this->resetForm();
        $this->isModalOpen = true;
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $this->selected_id = $id;
        $this->name = $category->name;
        $this->type = $category->type;
        $this->organization_type = $category->organization_type ?? 'perumahan';
        $this->fund_type = $category->fund_type ?? '';
        $this->isModalOpen = true;
    }

    public function store()
    {
        $this->validate();

        Category::updateOrCreate(['id' => $this->selected_id], [
            'name' => $this->name,
            'type' => $this->type,
            'organization_type' => $this->organization_type,
            'fund_type' => $this->fund_type ?: null,
        ]);

        session()->flash('success',
            $this->selected_id ? 'Kategori berhasil diperbarui.' : 'Kategori berhasil dibuat.');

        $this->closeModal();
    }

    public function delete($id)
    {
        // Validasi: Jangan hapus kategori jika sudah dipakai di transaksi
        $category = Category::withCount('transactions')->find($id);

        if ($category->transactions_count > 0) {
            session()->flash('error', 'Gagal! Kategori ini sudah digunakan di transaksi.');
            return;
        }

        $category->delete();
        session()->flash('success', 'Kategori berhasil dihapus.');
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->reset(['name', 'type', 'organization_type', 'fund_type', 'selected_id']);
        $this->type = 'income';
        $this->organization_type = 'perumahan';
        $this->fund_type = '';
    }
}
