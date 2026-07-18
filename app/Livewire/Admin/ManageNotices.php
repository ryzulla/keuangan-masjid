<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Notice;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class ManageNotices extends Component
{
    use WithPagination;

    public bool $isModalOpen = false;
    public ?int $editingId = null;
    public string $title = '';
    public string $content = '';
    public string $priority = 'info';
    public bool $is_published = true;
    public ?string $expires_at = null;

    protected $rules = [
        'title'       => 'required|string|max:255',
        'content'     => 'required|string',
        'priority'    => 'required|in:info,warning,urgent',
        'is_published'=> 'boolean',
        'expires_at'  => 'nullable|date|after:now',
    ];

    public function openModal(): void
    {
        $this->editingId   = null;
        $this->title       = '';
        $this->content     = '';
        $this->priority    = 'info';
        $this->is_published = true;
        $this->expires_at  = null;
        $this->isModalOpen = true;
    }

    public function edit(Notice $notice): void
    {
        $this->editingId   = $notice->id;
        $this->title       = $notice->title;
        $this->content     = $notice->content;
        $this->priority    = $notice->priority;
        $this->is_published = $notice->is_published;
        $this->expires_at  = $notice->expires_at?->format('Y-m-d');
        $this->isModalOpen = true;
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'title'        => $this->title,
            'content'      => $this->content,
            'priority'     => $this->priority,
            'is_published' => $this->is_published,
            'published_at' => $this->editingId ? null : now(),
            'expires_at'   => $this->expires_at ? $this->expires_at . ' 23:59:59' : null,
            'created_by'   => auth()->id(),
        ];

        if ($this->editingId) {
            Notice::findOrFail($this->editingId)->update($data);
            session()->flash('success', 'Pengumuman berhasil diperbarui.');
        } else {
            Notice::create($data);
            session()->flash('success', 'Pengumuman berhasil dibuat.');
        }

        $this->isModalOpen = false;
    }

    public function delete(Notice $notice): void
    {
        $notice->delete();
        session()->flash('success', 'Pengumuman berhasil dihapus.');
    }

    public function togglePublish(Notice $notice): void
    {
        $notice->update(['is_published' => !$notice->is_published]);
    }

    public function render()
    {
        return view('livewire.admin.manage-notices', [
            'notices' => Notice::with('author')->latest()->paginate(10),
        ]);
    }
}
