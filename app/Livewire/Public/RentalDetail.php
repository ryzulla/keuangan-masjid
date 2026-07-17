<?php
namespace App\Livewire\Public;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\HouseBlock;

#[Layout('layouts.public')]
class RentalDetail extends Component
{
    public HouseBlock $houseBlock;
    public int $activePhotoIndex = 0;

    public function mount(int $id): void
    {
        $this->houseBlock = HouseBlock::with(['photos', 'owners'])
            ->where('is_for_rent', true)
            ->findOrFail($id);
    }

    public function setActivePhoto(int $index): void
    {
        $this->activePhotoIndex = $index;
    }

    public function prevPhoto(): void
    {
        $total = $this->houseBlock->photos->count();
        if ($total === 0) return;
        $this->activePhotoIndex = ($this->activePhotoIndex - 1 + $total) % $total;
    }

    public function nextPhoto(): void
    {
        $total = $this->houseBlock->photos->count();
        if ($total === 0) return;
        $this->activePhotoIndex = ($this->activePhotoIndex + 1) % $total;
    }

    public function render()
    {
        $owner = $this->houseBlock->owners->first();

        return view('livewire.public.rental-detail', compact('owner'));
    }
}
