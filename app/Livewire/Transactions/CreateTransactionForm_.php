<?php
namespace App\Livewire\Transactions;
use Livewire\Component;
use App\Models\Account;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout; // <-- Penting untuk layout

#[Layout('layouts.app')] // <-- Menentukan layout utama
class CreateTransactionForm extends Component
{
    public $type = 'debit';
    public $amount;
    public $description;
    public $account_id;
    public $category_id;
    public $transaction_date;

    public $accounts = [];
    public $categories = [];

    public function rules()
    {
        return [
            'type' => 'required|in:debit,credit',
            'amount' => 'required|numeric|min:1',
            'description' => 'required|string|max:255',
            'account_id' => 'required|exists:accounts,id',
            'category_id' => [
                'required',
                Rule::exists('categories', 'id')->where(function ($query) {
                    $query->where('type', $this->type === 'debit' ? 'income' : 'expense');
                }),
            ],
            'transaction_date' => 'required|date',
        ];
    }

    public function messages()
    {
        return [
            'category_id.exists' => 'Kategori yang dipilih tidak valid untuk tipe transaksi ini.',
        ];
    }

    public function mount()
    {
        $this->accounts = Account::orderBy('name')->get();
        $this->transaction_date = now()->format('Y-m-d');
        $this->loadCategories();
    }

    public function updatedType()
    {
        $this->loadCategories();
    }

    public function loadCategories()
    {
        $this->category_id = null;
        if ($this->type === 'debit') {
            $this->categories = Category::where('type', 'income')->orderBy('name')->get();
        } elseif ($this->type === 'credit') {
            $this->categories = Category::where('type', 'expense')->orderBy('name')->get();
        } else {
            $this->categories = [];
        }
    }

    public function saveTransaction()
    {
        $this->validate();

        try {
            DB::beginTransaction();
            Transaction::create([
                'type' => $this->type,
                'amount' => $this->amount,
                'description' => $this->description,
                'account_id' => $this->account_id,
                'category_id' => $this->category_id,
                'transaction_date' => $this->transaction_date,
                'user_id' => Auth::id(),
            ]);
            DB::commit();
            session()->flash('success', 'Transaksi berhasil disimpan.');
            $this->reset(['amount', 'description', 'category_id']);
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.transactions.create-transaction-form');
    }
}
