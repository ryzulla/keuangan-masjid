<?php
use Illuminate\Support\Facades\Route;
use App\Livewire\WelcomePage;
use App\Livewire\Dashboard;
use App\Livewire\Accounts\ManageAccounts;
use App\Livewire\Categories\ManageCategories;
use App\Livewire\Campaigns\ManageCampaigns;
use App\Livewire\Campaigns\CampaignDetail;
use App\Livewire\Campaigns\CreateEditCampaign;
use App\Livewire\Transactions\BukuBesar;
use App\Livewire\Transactions\TransaksiPerumahan;
use App\Livewire\Reports\CashFlowReport;
use App\Livewire\Reports\BalanceSheetReport;
use App\Livewire\Admin\ManageUsers;
use App\Livewire\Admin\RoleAccessSettings;
use App\Livewire\Admin\AppSettings;
use App\Livewire\Residents\ManageResidents;
use App\Livewire\Residents\CreateEditResident;
use App\Livewire\Residents\ResidentDetail;
use App\Livewire\HouseBlocks\ManageHouseBlocks;
use App\Livewire\HouseBlocks\HouseBlockDetail;
use App\Livewire\IPL\ManageIPL;
use App\Livewire\IPL\IPLReport;
use App\Livewire\IPL\TariffSettings;
use App\Livewire\Penghuni\Login as PenghuniLogin;
use App\Livewire\Penghuni\Dashboard as PenghuniDashboard;
use App\Livewire\Penghuni\ProgramPortal;
use App\Livewire\Penghuni\IplPortal;
use App\Livewire\Penghuni\KeluargaPortal;
use App\Livewire\Penghuni\CreateEditFamilyMember;
use App\Livewire\Penghuni\EditDataDiri;
use App\Livewire\Penghuni\RumahSaya;
use App\Livewire\Penghuni\DetailRumah;
use App\Livewire\Penghuni\CampaignDetail as PenghuniCampaignDetail;
use App\Livewire\Penghuni\Settings as PenghuniSettings;
use App\Livewire\Penghuni\KeuanganPortal;
use App\Livewire\Admin\PaymentRequestAdmin;

Route::get('/', WelcomePage::class)->name('welcome');
Route::get('/sewa/{id}', \App\Livewire\Public\RentalDetail::class)->name('rental.detail');
Route::get('/tes-murni', function () {
    return 'Halo, server membaca file ini!';
});

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('dashboard', Dashboard::class)->name('dashboard');
    Route::view('profile', 'profile')->name('profile.edit');

    // --- Perumahan: Data Penghuni & Blok ---
    Route::middleware(['module:perumahan', 'can:manage-residents'])->group(function () {
        Route::get('residents', ManageResidents::class)->name('residents.index');
        Route::get('residents/create', CreateEditResident::class)->name('residents.create');
        Route::get('residents/{resident}/edit', CreateEditResident::class)->name('residents.edit');
        Route::get('residents/{resident}', ResidentDetail::class)->name('residents.show');
        Route::get('house-blocks', ManageHouseBlocks::class)->name('house-blocks.index');
        Route::get('house-blocks/{houseBlock}', HouseBlockDetail::class)->name('house-blocks.show');
    });

    // --- Perumahan: IPL ---
    Route::middleware(['module:perumahan', 'can:manage-ipl'])->group(function () {
        Route::get('ipl', ManageIPL::class)->name('ipl.index');
        Route::get('ipl/report', IPLReport::class)->name('ipl.report');
        Route::get('ipl/tariffs', TariffSettings::class)->name('ipl.tariffs');
    });

    // --- Perumahan: Transaksi Perumahan ---
    Route::middleware(['module:perumahan', 'can:manage-perumahan'])->group(function () {
        Route::get('perumahan/transaksi', TransaksiPerumahan::class)->name('perumahan.transaksi');
    });

    // --- DKM: Buku Besar / Transaksi DKM ---
    Route::middleware(['module:dkm', 'can:manage-dkm'])->group(function () {
        Route::get('transactions', BukuBesar::class)->name('transactions.index');
    });

    // --- Program / Campaign (DKM & Perumahan) ---
    Route::middleware('can:manage-programs')->group(function () {
        Route::get('campaigns', ManageCampaigns::class)->name('campaigns.index');
        Route::get('campaigns/create', CreateEditCampaign::class)->name('campaigns.create');
        Route::get('campaigns/{campaign}/edit', CreateEditCampaign::class)->name('campaigns.edit');
        Route::get('campaigns/{campaign}', CampaignDetail::class)->name('campaigns.show');
    });

    // --- Master Data: Akun & Kategori ---
    Route::middleware('can:manage-transactions')->group(function () {
        Route::get('accounts', ManageAccounts::class)->name('accounts.index');
        Route::get('categories', ManageCategories::class)->name('categories.index');
    });

    // --- Laporan ---
    Route::middleware('can:view-reports')->group(function () {
        Route::get('reports/cash-flow', CashFlowReport::class)->name('reports.cashflow');
        Route::get('reports/balance-sheet', BalanceSheetReport::class)->name('reports.balancesheet');
    });

    // --- Manajemen Pengguna (gate terpisah agar bisa diberikan tanpa akses admin penuh) ---
    Route::middleware('can:manage-users')->group(function () {
        Route::get('users', ManageUsers::class)->name('users.index');
    });

    // --- Admin (role, pengaturan, pengumuman, dll) ---
    Route::middleware('can:manage-admin')->group(function () {
        Route::get('settings/aplikasi', AppSettings::class)->name('settings.app');
        Route::get('role-access', RoleAccessSettings::class)->name('role-access.index');
        Route::get('notices', \App\Livewire\Admin\ManageNotices::class)->name('notices.index');
        Route::get('citizen-reports', \App\Livewire\Admin\ManageCitizenReports::class)->name('citizen-reports.index');
        Route::get('emergency-alerts', \App\Livewire\Admin\ManageEmergencyAlerts::class)->name('emergency-alerts.index');
    });
});

// ─── Portal Penghuni ──────────────────────────────────────────────────────────
Route::prefix('penghuni')->name('penghuni.')->group(function () {

    // Guest routes
    Route::middleware('guest:resident')->group(function () {
        Route::get('login', PenghuniLogin::class)->name('login');
        Route::get('lupa-password', \App\Livewire\Penghuni\ForgotPassword::class)->name('password.request');
    });

    // Logout
    Route::post('logout', function () {
        \Illuminate\Support\Facades\Auth::guard('resident')->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('penghuni.login');
    })->name('logout');

    // Notification mark-all-read for penghuni
    Route::post('notifikasi/read-all', function () {
        auth('resident')->user()?->unreadNotifications->markAsRead();
        return response()->noContent();
    })->middleware('resident.auth')->name('notifikasi.read-all');

    // Auth routes
    Route::middleware('resident.auth')->group(function () {
        Route::get('dashboard', PenghuniDashboard::class)->name('dashboard');
        Route::get('program', ProgramPortal::class)->name('program');
        Route::get('ipl', IplPortal::class)->middleware('module:perumahan')->name('ipl');
        Route::get('keluarga', KeluargaPortal::class)->name('keluarga');
        Route::get('keluarga/data-diri', EditDataDiri::class)->name('keluarga.diri');
        Route::get('keluarga/tambah', CreateEditFamilyMember::class)->name('keluarga.create');
        Route::get('keluarga/{member}/edit', CreateEditFamilyMember::class)->name('keluarga.edit');
        Route::get('program/{campaign}', PenghuniCampaignDetail::class)->name('program.detail');
        Route::get('rumah-saya', RumahSaya::class)->middleware('module:perumahan')->name('rumah-saya');
        Route::get('rumah-saya/{houseBlock}', DetailRumah::class)->middleware('module:perumahan')->name('detail-rumah');
        Route::get('keuangan', KeuanganPortal::class)->name('keuangan');
        Route::get('settings', PenghuniSettings::class)->name('settings');

        // Jembatan ke area admin untuk penghuni yang dipromosikan menjadi admin/pengurus.
        Route::get('masuk-admin', function () {
            $resident  = \Illuminate\Support\Facades\Auth::guard('resident')->user();
            $adminUser = $resident?->adminUser;
            abort_unless($adminUser && $adminUser->is_active, 403);
            \Illuminate\Support\Facades\Auth::guard('web')->login($adminUser);
            return redirect()->route('dashboard');
        })->name('admin-bridge');
    });
});

// ─── Admin: Konfirmasi Pembayaran Penghuni ────────────────────────────────────
Route::middleware(['auth', 'verified', 'can:approve-payments'])->group(function () {
    Route::get('payment-requests', PaymentRequestAdmin::class)->name('payment-requests.index');
});

// Admin notification mark-all-read
Route::post('admin/notifikasi/read-all', function () {
    auth()->user()?->unreadNotifications->markAsRead();
    return response()->noContent();
})->middleware(['auth', 'verified'])->name('admin.notifikasi.read-all');

require __DIR__.'/auth.php';
