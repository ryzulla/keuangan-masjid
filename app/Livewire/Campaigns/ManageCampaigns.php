<?php
namespace App\Livewire\Campaigns;

use Livewire\Component;
use App\Models\Campaign;
use Illuminate\Support\Facades\Storage; // <-- Impor Storage
use Illuminate\Support\Facades\Log;      // <-- Impor Log
use Illuminate\Support\Facades\DB;       // <-- Impor DB
use Livewire\WithPagination;
use Livewire\WithFileUploads; // <-- Impor WithFileUploads
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;     // <-- Impor Rule

#[Layout('layouts.app')] // Tentukan layout utama
class ManageCampaigns extends Component
{
    use WithPagination, WithFileUploads; // <-- Gunakan WithFileUploads

    // --- Properti Form ---
    public $name;
    // Deskripsi akan di-bind via JS dari CKEditor
    #[Rule('nullable|string')]
    public $description = '';
    public $target_amount;
    public $start_date;
    public $end_date;
    public $status = 'active';
    public $image = null; // <-- Properti untuk input file gambar
    public $existingImage = null; // <-- Untuk menampilkan/menghapus gambar lama

    // --- Properti State ---
    public $selected_id; // ID campaign yang diedit
    public $isModalOpen = false;

    /**
     * Aturan validasi untuk form modal.
     */
    protected function rules()
    {
        return [
            'name' => 'required|string|min:3|max:255',
            'description' => 'nullable|string', // Validasi dasar untuk string dari CKEditor
            'target_amount' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:active,completed,cancelled',
            // Validasi gambar (opsional, tipe image, maks 2MB)
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048', // Maks 2MB
        ];
    }

    /**
     * Pesan validasi kustom.
     */
    protected function messages() {
        return [
            'image.image' => 'File harus berupa gambar (jpg, jpeg, png, webp).',
            'image.max' => 'Ukuran gambar maksimal 2MB.',
            'name.required' => 'Nama program wajib diisi.',
            'start_date.required' => 'Tanggal mulai wajib diisi.',
            'end_date.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai.',
            'status.required' => 'Status program wajib dipilih.',
        ];
    }

    /**
     * Inisialisasi komponen.
     */
    public function mount() {
        // Set tanggal mulai default untuk form
        $this->start_date = now()->format('Y-m-d');
    }

    /**
     * Render view komponen.
     */
    public function render()
    {
        $campaigns = null; // Inisialisasi
        try {
            // Ambil data campaign dengan total donasi terkumpul
            $campaigns = Campaign::withSum('transactions', 'amount')
                             ->latest('start_date') // Urutkan berdasarkan tanggal mulai terbaru
                             ->paginate(10); // Paginasi data

        } catch (\Exception $e) {
            Log::error('Error rendering ManageCampaigns component: ' . $e->getMessage());
            session()->flash('render_error', 'Gagal memuat data program. Silakan cek log.');
            $campaigns = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10); // Paginator kosong
        }

        return view('livewire.campaigns.manage-campaigns', [
            'campaigns' => $campaigns
        ]);
    }

     /**
     * Hook saat properti image diupdate (file dipilih).
     * Validasi file dan reset preview gambar lama.
     */
    public function updatedImage()
    {
        $this->validateOnly('image'); // Validasi hanya properti image
        $this->existingImage = null; // Sembunyikan preview gambar lama
    }


    /**
     * Membuka modal untuk membuat program baru.
     */
    public function create() {
        $this->resetForm(); // Reset semua input form
        // Kirim event ke JS untuk membersihkan CKEditor
        $this->dispatch('resetCkEditorContent', editorId: 'campaignDescriptionCreate');
        $this->isModalOpen = true; // Buka modal
    }

    /**
     * Membuka modal untuk mengedit program.
     */
    public function edit($id) {
        try {
            $campaign = Campaign::findOrFail($id); // Cari campaign berdasarkan ID
            // Isi properti form dengan data campaign
            $this->selected_id = $id;
            $this->name = $campaign->name;
            $this->description = $campaign->description ?? ''; // Isi deskripsi (bisa null)
            $this->target_amount = $campaign->target_amount ?? null;
            $this->start_date = optional($campaign->start_date)->format('Y-m-d');
            $this->end_date = optional($campaign->end_date)->format('Y-m-d');
            $this->status = $campaign->status;
            $this->existingImage = $campaign->image; // Simpan path gambar lama
            $this->image = null; // Reset input file baru
            $this->isModalOpen = true; // Buka modal
            $this->resetErrorBag(); // Hapus error validasi lama
            session()->forget('modal_error'); // Hapus error custom lama

            // HAPUS DISPATCH EVENT UPDATE DARI SINI
            // $this->dispatch('updateCkEditorContent', ['editorId' => 'campaignDescriptionEdit'.$id, 'content' => $this->description]);
            // Biarkan Alpine 'x-watch' yang menanganinya

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
             Log::warning('Attempted to edit non-existent campaign ID: '.$id);
             session()->flash('error', 'Program yang ingin diedit tidak ditemukan.');
        } catch (\Exception $e) {
            Log::error('Error opening campaign edit modal for ID ' . $id . ': ' . $e->getMessage());
            session()->flash('error', 'Gagal memuat data program untuk diedit.');
        }
    }

    /**
     * Menyimpan data program (baru atau update).
     */
    public function store() {
         $validatedData = $this->validate(); // Lakukan validasi
         try {
             DB::beginTransaction(); // Mulai transaksi database

             $imagePath = $this->existingImage; // Default pakai path gambar lama

             // --- HANDLE UPLOAD GAMBAR BARU ---
             if ($this->image) {
                 // 1. Hapus gambar lama jika ada (saat update & gambar baru diupload)
                 if($this->selected_id && $this->existingImage) {
                     Storage::disk('public')->delete($this->existingImage);
                     Log::info("Deleted old campaign image during update: {$this->existingImage}");
                 }
                 // 2. Simpan gambar baru
                 $filename = $this->image->getClientOriginalName();
                 $path = 'campaign_images/' . date('Y/m');
                 $safeFilename = preg_replace('/[^A-Za-z0-9\.\-\_]/', '_', $filename);
                 $imagePath = $this->image->storeAs($path, uniqid() . '-' . $safeFilename , 'public');
                 Log::info("Stored new campaign image: {$imagePath}");
             }
             // --- AKHIR HANDLE UPLOAD GAMBAR ---

             // Siapkan data untuk disimpan/diupdate ke tabel 'campaigns'
             $campaignData = [
                'name' => $validatedData['name'],
                'description' => $this->description ?? '', // Ambil dari properti publik
                'target_amount' => $validatedData['target_amount'] ?? null,
                'start_date' => $validatedData['start_date'],
                'end_date' => $validatedData['end_date'] ?? null,
                'status' => $validatedData['status'],
                'image' => $imagePath, // Simpan path gambar baru atau path lama
             ];

              // Handle jika gambar lama dihapus manual via tombol 'Hapus' di modal
             if ($this->selected_id && $this->existingImage === null && !$this->image) {
                 $oldCampaign = Campaign::find($this->selected_id);
                 if ($oldCampaign && $oldCampaign->image) {
                      Storage::disk('public')->delete($oldCampaign->image);
                      Log::info("Deleted campaign image via remove button: {$oldCampaign->image}");
                 }
                 $campaignData['image'] = null; // Set path gambar jadi null di DB
             }

             // Update jika ada $selected_id, jika tidak Create baru
             Campaign::updateOrCreate(['id' => $this->selected_id], $campaignData);

             DB::commit(); // Konfirmasi transaksi database
             session()->flash('success', $this->selected_id ? 'Program berhasil diperbarui.' : 'Program berhasil dibuat.');
             $this->closeModal(); // Tutup modal

         } catch (\Exception $e) {
            DB::rollBack(); // Batalkan transaksi database jika ada error
            Log::error('Error saving campaign (ID: '.$this->selected_id.'): ' . $e->getMessage());
            // Hapus file gambar yang mungkin terlanjur terupload jika terjadi error saat simpan DB
            if (isset($imagePath) && $this->image && $imagePath !== $this->existingImage) {
                 Storage::disk('public')->delete($imagePath);
                 Log::warning("Rolled back campaign image upload due to DB error: {$imagePath}");
            }
            // Tampilkan pesan error di modal
            session()->flash('modal_error', 'Gagal menyimpan program: ' . $e->getMessage());
         }
    }

    /**
     * Menandai gambar lama untuk dihapus saat form disimpan.
     */
    public function removeImage() {
        $this->existingImage = null; // Tandai untuk dihapus saat store()
        $this->image = null; // Reset input file
        $this->resetErrorBag('image'); // Hapus error validasi gambar
        Log::info("Campaign image marked for removal (Campaign ID: {$this->selected_id})");
    }

    // --- Aksi Hapus & Tutup Modal ---

    public function confirmDelete($id) {
        $this->dispatch('show-campaign-delete-confirmation', id: $id);
    }

    public function delete($id) {
         try {
             DB::beginTransaction();
             $campaign = Campaign::withCount('donations')->find($id);
             if (!$campaign) { throw new \Exception("Program tidak ditemukan."); }
             if ($campaign->donations_count > 0) { throw new \Exception("Program ini sudah memiliki donasi terkait dan tidak bisa dihapus."); }

             $imageToDelete = $campaign->image;
             $campaign->delete();

             if ($imageToDelete) {
                 Storage::disk('public')->delete($imageToDelete);
                 Log::info("Deleted campaign image with record: {$imageToDelete}");
             }

             DB::commit();
             $this->dispatch('campaignDeleted');
             session()->flash('success', 'Program berhasil dihapus.');

         } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting campaign ID ' . $id . ': ' . $e->getMessage());
            $this->dispatch('deleteFailed', message: $e->getMessage());
         }
    }

    public function closeModal() {
        $this->isModalOpen = false;
        $this->resetForm();
    }

    private function resetForm() {
        $this->reset([
            'name', 'description', 'target_amount', 'end_date', 'status',
            'selected_id', 'image', 'existingImage'
        ]);
        $this->description = '';
        $this->start_date = now()->format('Y-m-d');
        $this->status = 'active';
        $this.resetErrorBag();
        session()->forget('modal_error');

        // Kirim event untuk membersihkan CKEditor
        $this->dispatch('resetCkEditorContent', editorId: 'campaignDescriptionCreate');
        if($this->selected_id) { // Juga coba reset editor edit
             $this->dispatch('resetCkEditorContent', editorId: 'campaignDescriptionEdit'.$this->selected_id);
        }
    }
}
