<?php

namespace App\Livewire;

use App\Models\ResidentPaymentRequest;
use Livewire\Component;

class NotificationBell extends Component
{
    /** Guard yang dipakai: 'web' (admin/pengurus) atau 'resident' (penghuni). */
    public string $guard = 'web';

    protected function authUser()
    {
        return auth($this->guard)->user();
    }

    public function getNotificationsProperty()
    {
        return $this->authUser()?->notifications()->latest()->take(12)->get() ?? collect();
    }

    public function getUnreadCountProperty(): int
    {
        return (int) ($this->authUser()?->unreadNotifications()->count() ?? 0);
    }

    /** Jumlah konfirmasi pembayaran yang menunggu verifikasi (khusus admin/pengurus). */
    public function getPendingPayCountProperty(): int
    {
        $user = $this->authUser();
        if ($this->guard !== 'web' || ! $user || ! $user->can('manage-ipl')) {
            return 0;
        }
        return (int) ResidentPaymentRequest::where('status', 'pending')->count();
    }

    /** Total badge = notifikasi belum dibaca + konfirmasi pending. */
    public function getBadgeCountProperty(): int
    {
        return $this->unreadCount + $this->pendingPayCount;
    }

    public function markAllRead(): void
    {
        $this->authUser()?->unreadNotifications->markAsRead();
    }

    /** Hapus semua notifikasi milik pengguna. */
    public function clearAll(): void
    {
        $this->authUser()?->notifications()->delete();
    }

    /** Tandai satu notifikasi terbaca lalu buka halaman terkait bila ada. */
    public function openNotification(string $id)
    {
        $user = $this->authUser();
        if (! $user) {
            return null;
        }

        $notif = $user->notifications()->find($id);
        if (! $notif) {
            return null;
        }

        if (is_null($notif->read_at)) {
            $notif->markAsRead();
        }

        $url = $notif->data['url'] ?? null;
        if ($url) {
            return $this->redirect($url, navigate: true);
        }

        return null;
    }

    public function render()
    {
        return view('livewire.notification-bell');
    }
}
