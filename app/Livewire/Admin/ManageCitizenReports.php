<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\CitizenReport;
use App\Models\Notice;

#[Layout('layouts.app')]
class ManageCitizenReports extends Component
{
    use WithPagination;

    public string $filterStatus = 'all';
    public bool $isPublishModalOpen = false;
    public ?int $publishingReportId = null;
    public string $publishTitle = '';
    public string $publishContent = '';
    public string $publishPriority = 'info';

    protected $rules = [
        'publishTitle'    => 'required|string|max:255',
        'publishContent'  => 'required|string',
        'publishPriority' => 'required|in:info,warning,urgent',
    ];

    protected $messages = [
        'publishTitle.required'    => 'Judul wajib diisi.',
        'publishContent.required'  => 'Isi pengumuman wajib diisi.',
        'publishPriority.required' => 'Prioritas wajib dipilih.',
    ];

    public function openPublishModal(CitizenReport $report): void
    {
        $categoryLabel = match($report->category) {
            'sakit'     => 'Kondisi Sakit',
            'meninggal' => 'Berita Duka',
            'lainnya'   => 'Info Lainnya',
        };
        $personName = $report->person_name ?? $report->resident->name;
        $priority = match($report->category) {
            'meninggal' => 'urgent',
            'sakit'     => 'info',
            default     => 'info',
        };

        $this->publishingReportId = $report->id;
        $this->publishTitle = "{$categoryLabel}: {$personName}";
        $this->publishContent = $report->description;
        $this->publishPriority = $priority;
        $this->isPublishModalOpen = true;
    }

    public function publishAsNotice(): void
    {
        $this->validate();

        $report = CitizenReport::findOrFail($this->publishingReportId);

        $notice = Notice::create([
            'title'        => $this->publishTitle,
            'content'      => $this->publishContent,
            'priority'     => $this->publishPriority,
            'is_published' => true,
            'published_at' => now(),
            'created_by'   => auth()->id(),
        ]);

        $report->update([
            'status'       => 'published',
            'notice_id'    => $notice->id,
            'published_by' => auth()->id(),
            'published_at' => now(),
        ]);

        $this->isPublishModalOpen = false;
        $this->publishingReportId = null;

        session()->flash('success', 'Laporan berhasil dipublikasikan sebagai pengumuman.');
    }

    public function dismiss(CitizenReport $report): void
    {
        $report->update(['status' => 'dismissed']);
        session()->flash('success', 'Laporan berhasil diabaikan.');
    }

    public function delete(CitizenReport $report): void
    {
        $report->delete();
        session()->flash('success', 'Laporan berhasil dihapus.');
    }

    public function render()
    {
        $query = CitizenReport::with('resident', 'notice', 'publisher')->latest();

        if ($this->filterStatus !== 'all') {
            $query->where('status', $this->filterStatus);
        }

        return view('livewire.admin.manage-citizen-reports', [
            'reports' => $query->paginate(10),
        ]);
    }
}
