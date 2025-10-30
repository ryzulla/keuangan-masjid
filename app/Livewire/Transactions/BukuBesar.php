<?php
namespace App\Livewire\Transactions;

use Livewire\Component;
use App\Models\Transaction;
use App\Models\Account;
use App\Models\Category;
use App\Models\Campaign;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TransactionsExport;
use Barryvdh\DomPDF\Facade\Pdf;

#[Layout('layouts.app')]
class BukuBesar extends Component
{
    use WithPagination, WithFileUploads;

    // --- Filter Properties ---
    public $startDate;
    public $endDate;
    public $selectedCategoryId = '';
    public $selectedCampaignId = '';
    public bool $showCampaignFilter = false;

    // --- Modal Form Properties ---
    public $type = 'debit';
    public $amount;
    public $description;
    public $account_id;
    public $category_id;
    public $transaction_date;
    public $campaign_id = null; // ID Campaign yang dipilih di modal
    public $donor_name = null; // <-- Properti Baru: Nama Donatur
    public $attachmentFile = null;
    public $existingAttachment = null;
    public bool $showCampaignDropdown = false; // Visibility dropdown campaign di modal

    // --- State Properties ---
    public $selected_id;
    public $isModalOpen = false;

    // --- Dropdown Data ---
    public $accounts = [];
    public $categories = [];
    public $availableCampaigns = [];
    public $filterCategories = [];

    // --- Program Category Names ---
    const PROGRAM_INCOME_CATEGORY_NAMES = ['Donasi Program'];
    const PROGRAM_EXPENSE_CATEGORY_NAMES = ['Pengeluaran Program'];

    /**
     * Aturan validasi untuk form modal.
     */
    protected function rules()
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
            // Campaign is required if the dropdown is shown
            'campaign_id' => [ Rule::requiredIf($this->showCampaignDropdown), 'nullable', 'exists:campaigns,id'],
            // Donor name is optional, only for debit
            'donor_name' => ['nullable', 'string', 'max:255', Rule::requiredIf(false)], // Selalu nullable
            'attachmentFile' => 'nullable|file|mimes:pdf,jpg,jpeg,png,webp|max:2048',
        ];
    }

    /**
     * Custom validation messages.
     */
    protected function messages()
    {
         return [
            'category_id.exists' => 'Kategori tidak valid untuk tipe transaksi ini.',
            'campaign_id.required' => 'Program/Kampanye wajib dipilih untuk kategori ini.',
            // ... (Pesan attachment) ...
        ];
    }

    /**
     * Component initialization.
     */
    public function mount()
    {
        $this->startDate = now()->subDays(30)->format('Y-m-d');
        $this->endDate = now()->format('Y-m-d');
        $this->accounts = Account::orderBy('name')->get();
        $this->availableCampaigns = Campaign::where('status', 'active')->orderBy('name')->get();
        $this->filterCategories = Category::orderBy('name')->get();
        $this->transaction_date = now()->format('Y-m-d');
        $this->loadCategories();
        $this->checkCampaignFilterVisibility();
        $this->checkIfModalCampaignShouldBeVisible();
    }

    /**
     * Method render utama untuk menampilkan view dan data.
     */
    public function render()
    {
         $transactions = null; $totalDebit = 0; $totalCredit = 0;
         try {
             $query = $this->getFilteredQuery();
             $totalDebit = (clone $query)->where('transactions.type', 'debit')->sum('amount');
             $totalCredit = (clone $query)->where('transactions.type', 'credit')->sum('amount');
             // Eager load campaign directly from transaction
             $transactions = $query->with('campaign')->paginate(15);
         } catch (\Exception $e) { /* ... (Error handling render) ... */ }

         return view('livewire.transactions.buku-besar', [
             'transactions' => $transactions,
             'totalDebit' => $totalDebit,
             'totalCredit' => $totalCredit,
         ]);
    }

    // --- Filter Hooks ---
    public function updatedStartDate() { $this->resetPage(); }
    public function updatedEndDate() { $this->resetPage(); }
    public function updatedSelectedCategoryId($value) {
        $this->resetPage();
        $this->checkCampaignFilterVisibility();
        if (!$this->showCampaignFilter) { $this->selectedCampaignId = ''; }
    }
    public function updatedSelectedCampaignId() { $this->resetPage(); }

    // --- Logika Modal ---

    /**
     * Dipanggil saat tipe transaksi di form modal berubah (wire:model.live="type")
     */
 // --- Modal Hooks ---
    public function updatedType() {
        $this->category_id = null; $this->campaign_id = null; $this->donor_name = null; // Reset donor too
        $this->loadCategories();
        $this->checkIfModalCampaignShouldBeVisible();
        $this->resetErrorBag(['category_id', 'campaign_id', 'donor_name']);
     }

    public function updatedCategoryId($value) {
         $this->checkIfModalCampaignShouldBeVisible();
         if (!$this->showCampaignDropdown) { $this->campaign_id = null; }
         $this->resetErrorBag('campaign_id');
    }

     public function updatedAttachmentFile() {
         $this->existingAttachment = null; // Hapus preview lama jika ada file baru
         $this->validateOnly('attachmentFile'); // Validasi file langsung
     }


    /**
     * Mengecek apakah dropdown campaign di MODAL harus muncul
     */
        /**
     * Mengecek apakah filter campaign (di luar modal) harus muncul berdasarkan kategori filter yang dipilih
     */
    private function checkCampaignFilterVisibility()
    {
        $shouldShow = false;
        if (!empty($this->selectedCategoryId)) {
            // Cari nama kategori yang dipilih dari koleksi filterCategories
            $selectedCategory = $this->filterCategories->firstWhere('id', $this->selectedCategoryId);
            // Cek apakah namanya ada di salah satu list program (income atau expense)
            if ($selectedCategory && (in_array($selectedCategory->name, self::PROGRAM_INCOME_CATEGORY_NAMES) || in_array($selectedCategory->name, self::PROGRAM_EXPENSE_CATEGORY_NAMES))) {
                $shouldShow = true;
            }
        }
        $this->showCampaignFilter = $shouldShow;
    }

    private function checkIfModalCampaignShouldBeVisible() {
         $shouldShow = false;
         if ($this->category_id) { // Cek hanya jika kategori sudah dipilih
             // Ambil kategori dari database untuk memastikan nama terbaru
             $selectedCategory = Category::find($this->category_id);
             // Cek apakah namanya ada di salah satu list program (income atau expense)
             if ($selectedCategory && (in_array($selectedCategory->name, self::PROGRAM_INCOME_CATEGORY_NAMES) || in_array($selectedCategory->name, self::PROGRAM_EXPENSE_CATEGORY_NAMES))) {
                 $shouldShow = true;
             }
         }
         $this->showCampaignDropdown = $shouldShow;
    }
    /**
     * Memuat kategori berdasarkan tipe transaksi saat ini
     */
    public function loadCategories() {
        $this->categories = collect(); // Reset dulu
        $categoryType = ($this->type === 'debit' ? 'income' : 'expense');
        // Muat kategori dan simpan ke properti
        $this->categories = Category::where('type', $categoryType)->orderBy('name')->get();
    }

    /**
     * Membuka modal untuk menambah transaksi baru
     */
    public function create() {
        $this->resetForm();
        $this->loadCategories();
        $this->checkIfModalCampaignShouldBeVisible();
        $this->isModalOpen = true;
    }

    /**
     * Membuka modal untuk mengedit transaksi
     */
    public function edit($id) {
        try {
            // Eager load donation (untuk donor_name)
            $transaction = Transaction::with('donation')->findOrFail($id);
            $this->type = $transaction->type;
            $this->loadCategories(); // Muat kategori sesuai tipe

            $this->selected_id = $id;
            $this->amount = $transaction->amount;
            $this->description = $transaction->description;
            $this->account_id = $transaction->account_id;
            $this->category_id = $transaction->category_id; // Set setelah loadCategories
            $this->transaction_date = $transaction->transaction_date->format('Y-m-d');
            // Ambil campaign_id LANGSUNG dari transaction
            $this->campaign_id = $transaction->campaign_id;
            // Ambil donor_name dari donation
            $this->donor_name = $transaction->donation?->donor_name;
            $this->existingAttachment = $transaction->attachment;
            $this->attachmentFile = null;

            $this->checkIfModalCampaignShouldBeVisible();
            $this->isModalOpen = true;
            $this->resetErrorBag();
            session()->forget('modal_error');

        } catch (\Exception $e) {
             Log::error('Error opening edit modal for transaction ID ' . $id . ': ' . $e->getMessage());
             session()->flash('error', 'Gagal memuat data transaksi untuk diedit.');
        }
    }

    /**
     * Menyimpan data (baru atau update) dari modal
     */
    public function store() {
         // Validasi SEMUA field dulu
         $validatedData = $this->validate();
         try {
             DB::beginTransaction();

             // 1. Handle Attachment (Logika tetap sama)
             $attachmentPath = $this->existingAttachment;
             if ($this->attachmentFile) {
                 if($this->selected_id && $this->existingAttachment) {
                     Storage::disk('public')->delete($this->existingAttachment);
                 }
                 $filename = $this->attachmentFile->getClientOriginalName();
                 $path = 'attachments/' . date('Y/m');
                 $attachmentPath = $this->attachmentFile->storeAs($path, uniqid() . '-' . preg_replace('/[^A-Za-z0-9\.\-\_]/', '', $filename) , 'public');
             }

             // 2. Siapkan data utama untuk tabel 'transactions'
             $transactionData = [
                'type' => $validatedData['type'],
                'amount' => $validatedData['amount'],
                'description' => $validatedData['description'],
                'account_id' => $validatedData['account_id'],
                'category_id' => $validatedData['category_id'],
                'transaction_date' => $validatedData['transaction_date'],
                'user_id' => Auth::id(),
                'campaign_id' => $this->showCampaignDropdown ? ($validatedData['campaign_id'] ?? null) : null,
                'attachment' => $attachmentPath,
             ];

             $transaction = null; // Inisialisasi

             // 3. Simpan atau Update Transaksi
             if($this->selected_id){ // Update
                 $transaction = Transaction::find($this->selected_id);
                 if ($transaction) {
                     // Handle penghapusan attachment manual
                     if ($this->existingAttachment === null && !$this->attachmentFile && $transaction->attachment) {
                         Storage::disk('public')->delete($transaction->attachment);
                         $transactionData['attachment'] = null;
                     }
                     $transaction->update($transactionData);
                     session()->flash('success', 'Transaksi berhasil diperbarui.');
                     // Hapus/Update donasi terkait (akan dibuat ulang jika perlu)
                     $donation = $transaction->donation; // Ambil donasi lama

                 } else { throw new \Exception("Transaksi tidak ditemukan."); }
             } else { // Create
                  $transaction = Transaction::create($transactionData);
                  $donation = null; // Belum ada donasi lama
                  session()->flash('success', 'Transaksi berhasil ditambahkan.');
             }

             // --- PERBAIKAN LOGIKA DONATION ---
             // Cek apakah ini transaksi PEMASUKAN (Debit)
             if ($transaction && $this->type == 'debit') {
                 // Ambil nama donatur dari data validasi (jika ada)
                 $donorName = $validatedData['donor_name'] ?? null;

                 // Kondisi untuk membuat/update record donasi:
                 // - Jika nama donatur diisi ATAU
                 // - Jika ini adalah kategori program income DAN campaign dipilih (untuk menyimpan campaign_id di donation jika perlu)
                 //   (Meskipun campaign_id sudah di transaction, menyimpan di donation bisa berguna jika desain berubah)
                 $isProgramIncome = $this->isProgramIncomeCategory($validatedData['category_id']);
                 $campaignSelected = $this->showCampaignDropdown && !empty($validatedData['campaign_id']);

                 if (!empty($donorName) || ($isProgramIncome && $campaignSelected)) {
                     // Data untuk tabel donations
                     $donationData = [
                        'donor_name' => $donorName,
                        'campaign_id' => $campaignSelected ? $validatedData['campaign_id'] : null, // Simpan campaign jika relevan
                        'type' => 'infaq', // Sesuaikan
                     ];

                     // Gunakan updateOrCreate untuk handle update/create
                     // Parameter pertama: Kunci unik (transaction_id)
                     // Parameter kedua: Data yang akan diisi/update
                     $transaction->donation()->updateOrCreate(
                         ['transaction_id' => $transaction->id], // Kunci pencarian
                         $donationData // Data untuk diisi/update
                     );
                 } else {
                     // Jika tidak ada nama donatur DAN bukan donasi program, hapus record donasi lama jika ada (saat update)
                     optional($transaction->donation)->delete();
                 }

             } else { // Jika ini PENGELUARAN (Credit)
                 // Pastikan tidak ada record donasi yang terkait (hapus jika ada saat update)
                 optional($transaction->donation)->delete();
             }
             // --- AKHIR PERBAIKAN ---

             DB::commit();
             $this->closeModal();
         } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error storing transaction (ID: '.$this->selected_id.'): ' . $e->getMessage());
            // Rollback attachment
            if (isset($attachmentPath) && $this->attachmentFile && $attachmentPath !== $this->existingAttachment) {
                 Storage::disk('public')->delete($attachmentPath);
                 Log::warning("Rolled back attachment upload: {$attachmentPath}");
            }
            session()->flash('modal_error', 'Gagal menyimpan transaksi: ' . $e->getMessage());
         }
    }

    /**
     * Helper untuk cek apakah kategori termasuk program income
     */
     private function isProgramIncomeCategory($categoryId)
     {
        if (!$categoryId) return false;
        $category = Category::find($categoryId);
        return $category && in_array($category->name, self::PROGRAM_INCOME_CATEGORY_NAMES);
     }

    /**
     * Menandai file attachment yang ada untuk dihapus (saat edit)
     * Penghapusan fisik terjadi di store() agar bisa di-rollback
     */
    public function removeAttachment()
    {
        $this->existingAttachment = null; // Tandai untuk dihapus saat store()
        $this->attachmentFile = null; // Reset input file juga
        $this->resetErrorBag('attachmentFile');
        Log::info("Attachment marked for removal during edit (Tx ID: {$this->selected_id})");
    }

    public function confirmDelete($id) { $this->dispatch('show-delete-confirmation', id: $id); }

    public function delete($id) {
        try {
            DB::beginTransaction();
            $transaction = Transaction::find($id);
            if (!$transaction) { throw new \Exception("Transaksi tidak ditemukan."); }

            // Simpan path attachment sebelum dihapus
            $attachmentToDelete = $transaction->attachment;

            // Hapus record Transaction (dan Donation via cascade)
            $transaction->delete(); // Observer akan adjust saldo

            // Hapus file attachment fisik SETELAH record DB berhasil dihapus
            if ($attachmentToDelete) {
                Storage::disk('public')->delete($attachmentToDelete);
                Log::info("Deleting attachment with transaction: {$attachmentToDelete}");
            }

            DB::commit();
            $this->dispatch('transactionDeleted'); // Kirim event sukses
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting transaction ID ' . $id . ': ' . $e->getMessage());
            $this->dispatch('deleteFailed', message: 'Gagal menghapus: ' . $e->getMessage()); // Kirim event gagal
        }
    }

    public function closeModal() {
        $this->isModalOpen = false;
        $this->resetForm();
        session()->forget('modal_error');
        $this->resetErrorBag(); // Hapus error validasi
    }

    private function resetForm() {
        $this->reset([
            'selected_id', 'amount', 'description', 'account_id', 'category_id',
            'campaign_id', 'attachmentFile', 'existingAttachment', 'donor_name' // <-- Tambah donor_name
        ]);
        $this->type = 'debit';
        $this->transaction_date = now()->format('Y-m-d');
        $this->showCampaignDropdown = false;
        $this->loadCategories();
        $this->resetErrorBag();
        session()->forget('modal_error');
    }


    // --- METHOD EXPORT ---

    /**
     * Helper untuk membangun query yang difilter (tanpa paginasi) untuk ekspor
     */
    private function getFilteredQuery()
    {
         $query = Transaction::query()
                ->with(['account', 'category', 'user', 'campaign', 'donation']) // Tambah campaign & donation
                ->whereBetween('transaction_date', [$this->startDate, $this->endDate]);

        if (!empty($this->selectedCategoryId)) {
            $query->where('category_id', $this->selectedCategoryId);
        }

        // Filter campaign LANGSUNG di tabel transactions
        if ($this->showCampaignFilter && !empty($this->selectedCampaignId)) {
            $query->where('campaign_id', $this->selectedCampaignId);
        }
        return $query->latest('transaction_date')->latest('id');
    }

    /**
     * Method untuk mengekspor ke Excel
     */
    public function exportExcel()
    {
        try {
            // Buat nama file dinamis
            $category = $this->selectedCategoryId ? Category::find($this->selectedCategoryId) : null;
            $campaign = ($this->showCampaignFilter && $this->selectedCampaignId) ? Campaign::find($this->selectedCampaignId) : null;
            $categoryName = $category ? str_replace([' ', '/'], '_', $category->name) : 'semua_kategori';
            $campaignName = $campaign ? '_'.str_replace([' ', '/'], '_', $campaign->name) : '';
            $filename = 'laporan_transaksi_' . $categoryName . $campaignName . '_' . $this->startDate .'_sd_'. $this->endDate .'.xlsx';

            // Kirim parameter filter ke class Export
            return Excel::download(new TransactionsExport(
                $this->startDate,
                $this->endDate,
                $this->selectedCategoryId,
                $this->selectedCampaignId,
                $this->showCampaignFilter
            ), $filename);
        } catch (\Exception $e) {
             Log::error('Error exporting Excel: ' . $e->getMessage());
             session()->flash('error', 'Gagal mengekspor ke Excel: ' . $e->getMessage());
             return null;
        }
    }

    /**
     * Method untuk mengekspor ke PDF
     */
    public function exportPdf()
    {
         try {
             // Buat nama file dinamis
             $category = $this->selectedCategoryId ? Category::find($this->selectedCategoryId) : null;
             $campaign = ($this->showCampaignFilter && $this->selectedCampaignId) ? Campaign::find($this->selectedCampaignId) : null;
             $categoryName = $category ? str_replace([' ', '/'], '_', $category->name) : 'semua_kategori';
             $campaignName = $campaign ? '_'.str_replace([' ', '/'], '_', $campaign->name) : '';
             $filename = 'laporan_transaksi_' . $categoryName . $campaignName . '_' . $this->startDate .'_sd_'. $this->endDate .'.pdf';

             // Ambil data yang sudah difilter (tanpa paginasi)
             $transactions = $this->getFilteredQuery()->get();

             // Load view PDF dengan data
             $pdf = Pdf::loadView('exports.transactions_pdf', [
                 'transactions' => $transactions,
                 'startDate' => $this->startDate,
                 'endDate' => $this->endDate,
                 'category' => $category, // Kirim objek kategori
                 'campaign' => $campaign, // Kirim objek campaign
             ])->setPaper('a4', 'landscape');

             // Download PDF
             return response()->streamDownload(fn() => print($pdf->output()), $filename);
         } catch (\Exception $e) {
             Log::error('Error exporting PDF: ' . $e->getMessage());
             session()->flash('error', 'Gagal mengekspor ke PDF: ' . $e->getMessage());
             return null;
         }
    }
}
