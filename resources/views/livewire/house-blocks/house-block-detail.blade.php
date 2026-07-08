<div>
<x-slot name="header">
    <h2 class="font-semibold text-base" style="color:#111827;">Blok {{ $houseBlock->block_code }}</h2>
</x-slot>

<div style="padding:1.5rem;max-width:1100px;margin:0 auto;" x-data>

    @if(session('success'))
        <div style="background:#e3f1ea;border:1px solid #0e6d4f;color:#12805c;padding:12px 16px;border-radius:8px;margin-bottom:1rem;">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div style="background:#f7e7e4;border:1px solid #f7e7e4;color:#c0453b;padding:12px 16px;border-radius:8px;margin-bottom:1rem;">
            {{ session('error') }}
        </div>
    @endif

    {{-- Block info card --}}
    <div style="background:#ffffff;border:1px solid #f5f6f8;border-radius:12px;padding:1.25rem;margin-bottom:1.5rem;">
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:1rem;">
            <div>
                <div style="font-size:0.7rem;color:#667085;text-transform:uppercase;letter-spacing:.05em;margin-bottom:2px;">Kode Blok</div>
                <div style="font-size:1.5rem;font-weight:700;color:#111827;">{{ $houseBlock->block_code }}</div>
            </div>
            <div>
                <div style="font-size:0.7rem;color:#667085;text-transform:uppercase;letter-spacing:.05em;margin-bottom:2px;">Status</div>
                <div style="font-size:0.95rem;color:{{ $houseBlock->is_active ? '#12805c' : '#c0453b' }};">
                    {{ $houseBlock->is_active ? 'Aktif' : 'Nonaktif' }}
                </div>
            </div>
            @if($houseBlock->notes)
            <div style="grid-column:1/-1;">
                <div style="font-size:0.7rem;color:#667085;text-transform:uppercase;letter-spacing:.05em;margin-bottom:2px;">Catatan</div>
                <div style="color:#344054;font-size:0.9rem;">{{ $houseBlock->notes }}</div>
            </div>
            @endif
        </div>
    </div>

    {{-- ══════════════ PEMILIK ══════════════ --}}
    <div style="background:#ffffff;border:1px solid #f5f6f8;border-radius:12px;margin-bottom:1rem;overflow:hidden;">
        <div style="padding:1rem 1.25rem;display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid {{ $openForm === 'owner' ? '#111827' : '#eef0f3' }};">
            <div style="display:flex;align-items:center;gap:0.75rem;">
                <span style="font-size:1.1rem;color:#111827;font-weight:600;">Pemilik</span>
                @if($currentOwner)
                    <span style="background:#e3f1ea;color:#12805c;padding:2px 10px;border-radius:9999px;font-size:0.78rem;">
                        {{ $currentOwner->resident->name ?? '—' }}
                    </span>
                    <span style="color:#7c8698;font-size:0.8rem;">sejak {{ $currentOwner->resident_since?->format('d M Y') ?? '—' }}</span>
                @else
                    <span style="background:#f7e7e4;color:#c0453b;padding:2px 10px;border-radius:9999px;font-size:0.78rem;">Belum ada pemilik</span>
                @endif
            </div>
            <button wire:click="{{ $openForm === 'owner' ? 'cancelForm' : 'openOwnerForm' }}"
                    style="background:transparent;border:1px solid #111827;color:#111827;padding:5px 14px;border-radius:6px;cursor:pointer;font-size:0.82rem;">
                {{ $openForm === 'owner' ? 'Batal' : ($currentOwner ? 'Ganti Pemilik' : 'Tetapkan Pemilik') }}
            </button>
        </div>

        @if($openForm === 'owner')
        <form wire:submit.prevent="saveOwner" style="padding:1.25rem;">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <div>
                    <label style="font-size:0.8rem;color:#475467;display:block;margin-bottom:4px;">Penghuni <span style="color:#c0453b;">*</span></label>
                    <select wire:model="ownerResidentId" style="width:100%;background:#ffffff;border:1px solid #e4e7ec;box-shadow:0 1px 2px rgba(16,24,40,0.04),0 8px 20px -8px rgba(16,24,40,0.06);color:#1d2939;padding:8px 10px;border-radius:6px;font-size:0.9rem;">
                        <option value="">— Pilih penghuni —</option>
                        @foreach($residents as $r)
                            <option value="{{ $r->id }}">{{ $r->name }}</option>
                        @endforeach
                    </select>
                    @error('ownerResidentId') <p style="color:#c0453b;font-size:0.78rem;margin-top:2px;">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label style="font-size:0.8rem;color:#475467;display:block;margin-bottom:4px;">Sejak Tanggal <span style="color:#c0453b;">*</span></label>
                    <input type="date" wire:model="ownerSince"
                           style="width:100%;background:#ffffff;border:1px solid #e4e7ec;box-shadow:0 1px 2px rgba(16,24,40,0.04),0 8px 20px -8px rgba(16,24,40,0.06);color:#1d2939;padding:8px 10px;border-radius:6px;font-size:0.9rem;">
                    @error('ownerSince') <p style="color:#c0453b;font-size:0.78rem;margin-top:2px;">{{ $message }}</p> @enderror
                </div>
                <div style="grid-column:1/-1;">
                    <label style="font-size:0.8rem;color:#475467;display:block;margin-bottom:4px;">Catatan</label>
                    <input type="text" wire:model="ownerNotes" placeholder="Opsional"
                           style="width:100%;background:#ffffff;border:1px solid #e4e7ec;box-shadow:0 1px 2px rgba(16,24,40,0.04),0 8px 20px -8px rgba(16,24,40,0.06);color:#1d2939;padding:8px 10px;border-radius:6px;font-size:0.9rem;">
                </div>
            </div>
            <div style="margin-top:1rem;display:flex;gap:0.5rem;">
                <button type="submit"
                        style="background:#111827;color:#ffffff;padding:8px 20px;border:none;border-radius:6px;cursor:pointer;font-weight:600;font-size:0.9rem;">
                    Simpan Pemilik
                </button>
                <button type="button" wire:click="cancelForm"
                        style="background:#eef0f3;border:1px solid #cdd2da;color:#475467;padding:8px 16px;border-radius:6px;cursor:pointer;font-size:0.9rem;">
                    Batal
                </button>
            </div>
        </form>
        @endif
    </div>

    {{-- ══════════════ PENYEWA ══════════════ --}}
    <div style="background:#ffffff;border:1px solid #f5f6f8;border-radius:12px;margin-bottom:1rem;overflow:hidden;">
        <div style="padding:1rem 1.25rem;display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid {{ $openForm === 'tenant' ? '#111827' : '#eef0f3' }};">
            <div style="display:flex;align-items:center;gap:0.75rem;">
                <span style="font-size:1.1rem;color:#111827;font-weight:600;">Penyewa</span>
                @if($currentTenant)
                    <span style="background:#1d2939;color:#2563eb;padding:2px 10px;border-radius:9999px;font-size:0.78rem;">
                        {{ $currentTenant->resident->name ?? '—' }}
                    </span>
                    <span style="color:#667085;font-size:0.8rem;">({{ $currentTenant->ownership_label }})</span>
                    @if($currentTenant->contract_start_date)
                        <span style="color:#7c8698;font-size:0.8rem;">{{ $currentTenant->contract_period_label }}</span>
                    @endif
                @else
                    <span style="color:#7c8698;font-size:0.82rem;">Tidak ada penyewa aktif</span>
                @endif
            </div>
            <div style="display:flex;gap:0.5rem;">
                @if($currentTenant && $openForm !== 'end_tenant')
                <button wire:click="openEndTenantForm({{ $currentTenant->id }})"
                        style="background:transparent;border:1px solid #c0453b;color:#c0453b;padding:5px 12px;border-radius:6px;cursor:pointer;font-size:0.82rem;">
                    Akhiri Kontrak
                </button>
                @endif
                <button wire:click="{{ $openForm === 'tenant' ? 'cancelForm' : 'openTenantForm' }}"
                        style="background:transparent;border:1px solid #111827;color:#111827;padding:5px 14px;border-radius:6px;cursor:pointer;font-size:0.82rem;">
                    {{ $openForm === 'tenant' ? 'Batal' : ($currentTenant ? 'Ganti Penyewa' : 'Tambah Penyewa') }}
                </button>
            </div>
        </div>

        @if($openForm === 'tenant')
        <form wire:submit.prevent="saveTenant" style="padding:1.25rem;">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <div>
                    <label style="font-size:0.8rem;color:#475467;display:block;margin-bottom:4px;">Penghuni <span style="color:#c0453b;">*</span></label>
                    <select wire:model="tenantResidentId" style="width:100%;background:#ffffff;border:1px solid #e4e7ec;box-shadow:0 1px 2px rgba(16,24,40,0.04),0 8px 20px -8px rgba(16,24,40,0.06);color:#1d2939;padding:8px 10px;border-radius:6px;font-size:0.9rem;">
                        <option value="">— Pilih penghuni —</option>
                        @foreach($residents as $r)
                            <option value="{{ $r->id }}">{{ $r->name }}</option>
                        @endforeach
                    </select>
                    @error('tenantResidentId') <p style="color:#c0453b;font-size:0.78rem;margin-top:2px;">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label style="font-size:0.8rem;color:#475467;display:block;margin-bottom:4px;">Tipe Sewa <span style="color:#c0453b;">*</span></label>
                    <select wire:model="tenantType" style="width:100%;background:#ffffff;border:1px solid #e4e7ec;box-shadow:0 1px 2px rgba(16,24,40,0.04),0 8px 20px -8px rgba(16,24,40,0.06);color:#1d2939;padding:8px 10px;border-radius:6px;font-size:0.9rem;">
                        <option value="kontrak">Kontrak</option>
                        <option value="kos">Kos</option>
                    </select>
                </div>
                <div>
                    <label style="font-size:0.8rem;color:#475467;display:block;margin-bottom:4px;">Mulai Kontrak <span style="color:#c0453b;">*</span></label>
                    <input type="date" wire:model="tenantContractStart"
                           style="width:100%;background:#ffffff;border:1px solid #e4e7ec;box-shadow:0 1px 2px rgba(16,24,40,0.04),0 8px 20px -8px rgba(16,24,40,0.06);color:#1d2939;padding:8px 10px;border-radius:6px;font-size:0.9rem;">
                    @error('tenantContractStart') <p style="color:#c0453b;font-size:0.78rem;margin-top:2px;">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label style="font-size:0.8rem;color:#475467;display:block;margin-bottom:4px;">Selesai Kontrak</label>
                    <input type="date" wire:model="tenantContractEnd"
                           style="width:100%;background:#ffffff;border:1px solid #e4e7ec;box-shadow:0 1px 2px rgba(16,24,40,0.04),0 8px 20px -8px rgba(16,24,40,0.06);color:#1d2939;padding:8px 10px;border-radius:6px;font-size:0.9rem;">
                    @error('tenantContractEnd') <p style="color:#c0453b;font-size:0.78rem;margin-top:2px;">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label style="font-size:0.8rem;color:#475467;display:block;margin-bottom:4px;">Sewa / Bulan (Rp)</label>
                    <input type="number" wire:model="tenantMonthlyRent" placeholder="0" min="0"
                           style="width:100%;background:#ffffff;border:1px solid #e4e7ec;box-shadow:0 1px 2px rgba(16,24,40,0.04),0 8px 20px -8px rgba(16,24,40,0.06);color:#1d2939;padding:8px 10px;border-radius:6px;font-size:0.9rem;">
                </div>
                <div>
                    <label style="font-size:0.8rem;color:#475467;display:block;margin-bottom:4px;">Catatan</label>
                    <input type="text" wire:model="tenantNotes" placeholder="Opsional"
                           style="width:100%;background:#ffffff;border:1px solid #e4e7ec;box-shadow:0 1px 2px rgba(16,24,40,0.04),0 8px 20px -8px rgba(16,24,40,0.06);color:#1d2939;padding:8px 10px;border-radius:6px;font-size:0.9rem;">
                </div>
            </div>
            <div style="margin-top:1rem;display:flex;gap:0.5rem;">
                <button type="submit"
                        style="background:#111827;color:#ffffff;padding:8px 20px;border:none;border-radius:6px;cursor:pointer;font-weight:600;font-size:0.9rem;">
                    Simpan Penyewa
                </button>
                <button type="button" wire:click="cancelForm"
                        style="background:#eef0f3;border:1px solid #cdd2da;color:#475467;padding:8px 16px;border-radius:6px;cursor:pointer;font-size:0.9rem;">
                    Batal
                </button>
            </div>
        </form>
        @endif

        @if($openForm === 'end_tenant')
        <form wire:submit.prevent="endTenant" style="padding:1.25rem;background:#f7e7e4;">
            <p style="color:#344054;margin-bottom:0.75rem;font-size:0.9rem;">
                Akhiri kontrak <strong>{{ $currentTenant?->resident?->name }}</strong>. Rekaman akan disimpan sebagai riwayat.
            </p>
            <div style="max-width:250px;">
                <label style="font-size:0.8rem;color:#475467;display:block;margin-bottom:4px;">Tanggal Berakhir <span style="color:#c0453b;">*</span></label>
                <input type="date" wire:model="endTenantDate"
                       style="width:100%;background:#ffffff;border:1px solid #f7e7e4;color:#1d2939;padding:8px 10px;border-radius:6px;font-size:0.9rem;">
                @error('endTenantDate') <p style="color:#c0453b;font-size:0.78rem;margin-top:2px;">{{ $message }}</p> @enderror
            </div>
            <div style="margin-top:1rem;display:flex;gap:0.5rem;">
                <button type="submit"
                        style="background:#c0453b;color:#1d2939;padding:8px 20px;border:none;border-radius:6px;cursor:pointer;font-weight:600;font-size:0.9rem;">
                    Akhiri Kontrak
                </button>
                <button type="button" wire:click="cancelForm"
                        style="background:#eef0f3;border:1px solid #cdd2da;color:#475467;padding:8px 16px;border-radius:6px;cursor:pointer;font-size:0.9rem;">
                    Batal
                </button>
            </div>
        </form>
        @endif
    </div>

    {{-- ══════════════ RIWAYAT ══════════════ --}}
    <div style="background:#ffffff;border:1px solid #f5f6f8;border-radius:12px;margin-bottom:1rem;">
        <div style="padding:1rem 1.25rem;border-bottom:1px solid #eef0f3;">
            <span style="font-size:1.05rem;color:#111827;font-weight:600;">Riwayat Penghuni</span>
        </div>
        <div style="padding:1rem 1.25rem;">
            @if($allHistory->isEmpty())
                <p style="color:#7c8698;font-size:0.9rem;text-align:center;padding:1rem 0;">Belum ada riwayat penghuni.</p>
            @else
            <div class="hidden md:block" style="overflow-x:auto;">
                <table style="width:100%;border-collapse:collapse;font-size:0.85rem;">
                    <thead>
                        <tr style="border-bottom:1px solid #f5f6f8;">
                            <th style="text-align:left;padding:8px 10px;color:#667085;font-weight:500;">Nama</th>
                            <th style="text-align:left;padding:8px 10px;color:#667085;font-weight:500;">Status</th>
                            <th style="text-align:left;padding:8px 10px;color:#667085;font-weight:500;">Mulai</th>
                            <th style="text-align:left;padding:8px 10px;color:#667085;font-weight:500;">Selesai</th>
                            <th style="text-align:left;padding:8px 10px;color:#667085;font-weight:500;">Periode Kontrak</th>
                            <th style="text-align:left;padding:8px 10px;color:#667085;font-weight:500;">Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allHistory as $a)
                        <tr style="border-bottom:1px solid #ffffff;{{ $a->is_current ? 'background:rgba(18,128,92,0.08);' : '' }}">
                            <td style="padding:8px 10px;">
                                @if($a->resident)
                                    <a href="{{ route('residents.show', $a->resident) }}"
                                       style="color:#111827;text-decoration:none;">
                                        {{ $a->resident->name }}
                                    </a>
                                @else
                                    <span style="color:#7c8698;">—</span>
                                @endif
                                @if($a->is_current)
                                    <span style="background:#e3f1ea;color:#12805c;padding:1px 6px;border-radius:4px;font-size:0.7rem;margin-left:4px;">Aktif</span>
                                @else
                                    <span style="background:#f7e7e4;color:#c0453b;padding:1px 6px;border-radius:4px;font-size:0.7rem;margin-left:4px;">Non-Aktif</span>
                                @endif
                            </td>
                            <td style="padding:8px 10px;">
                                <span style="padding:2px 8px;border-radius:9999px;font-size:0.75rem;
                                    {{ $a->ownership_type === 'pemilik' ? 'background:#e3f1ea;color:#12805c;' : 'background:#1d2939;color:#2563eb;' }}">
                                    {{ $a->ownership_label }}
                                </span>
                            </td>
                            <td style="padding:8px 10px;color:#344054;">{{ $a->resident_since?->format('d M Y') ?? '—' }}</td>
                            <td style="padding:8px 10px;color:#344054;">
                                {{ $a->ended_at ? $a->ended_at->format('d M Y') : '—' }}
                            </td>
                            <td style="padding:8px 10px;color:#667085;font-size:0.8rem;">
                                {{ $a->contract_period_label ?: '—' }}
                                @if($a->monthly_rent)
                                    <br><span style="color:#667085;">Rp {{ number_format($a->monthly_rent, 0, ',', '.') }}/bln</span>
                                @endif
                            </td>
                            <td style="padding:8px 10px;color:#7c8698;font-size:0.8rem;">{{ $a->notes ?? '—' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="md:hidden divide-y" style="border-color:#eef0f3;">
                @foreach($allHistory as $a)
                <div class="py-3" wire:key="hist-{{ $a->id }}">
                    <div class="flex items-center justify-between gap-2">
                        <div class="min-w-0">
                            @if($a->resident)
                                <a href="{{ route('residents.show', $a->resident) }}"
                                   class="font-medium" style="color:#111827;text-decoration:none;">
                                    {{ $a->resident->name }}
                                </a>
                            @else
                                <span style="color:#7c8698;">—</span>
                            @endif
                            @if($a->is_current)
                                <span style="background:#e3f1ea;color:#12805c;padding:1px 6px;border-radius:4px;font-size:0.7rem;margin-left:4px;">Aktif</span>
                            @else
                                <span style="background:#f7e7e4;color:#c0453b;padding:1px 6px;border-radius:4px;font-size:0.7rem;margin-left:4px;">Non-Aktif</span>
                            @endif
                        </div>
                        <span class="shrink-0" style="padding:2px 8px;border-radius:9999px;font-size:0.75rem;
                            {{ $a->ownership_type === 'pemilik' ? 'background:#e3f1ea;color:#12805c;' : 'background:#1d2939;color:#2563eb;' }}">
                            {{ $a->ownership_label }}
                        </span>
                    </div>
                    <div class="grid grid-cols-2 gap-x-4 gap-y-1 mt-2 text-xs">
                        <div><span class="block" style="color:#667085;">Mulai</span><span style="color:#344054;">{{ $a->resident_since?->format('d M Y') ?? '—' }}</span></div>
                        <div><span class="block" style="color:#667085;">Selesai</span><span style="color:#344054;">{{ $a->ended_at ? $a->ended_at->format('d M Y') : '—' }}</span></div>
                    </div>
                    @if($a->contract_period_label || $a->monthly_rent)
                    <div class="mt-1 text-xs" style="color:#667085;">
                        {{ $a->contract_period_label ?: '—' }}
                        @if($a->monthly_rent)
                            <span style="color:#667085;"> · Rp {{ number_format($a->monthly_rent, 0, ',', '.') }}/bln</span>
                        @endif
                    </div>
                    @endif
                    @if($a->notes)
                    <div class="mt-1 text-xs" style="color:#7c8698;">{{ $a->notes }}</div>
                    @endif
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>

    {{-- ══════════════ TAGIHAN IPL ══════════════ --}}
    <div style="background:#ffffff;border:1px solid #f5f6f8;border-radius:12px;">
        <div style="padding:1rem 1.25rem;border-bottom:1px solid #eef0f3;display:flex;align-items:center;justify-content:space-between;">
            <span style="font-size:1.05rem;color:#111827;font-weight:600;">Tagihan IPL (12 Bulan Terakhir)</span>
            <a href="{{ route('ipl.index') }}" style="color:#667085;font-size:0.82rem;text-decoration:none;">Lihat Semua →</a>
        </div>
        <div style="padding:1rem 1.25rem;">
            @if($recentBillings->isEmpty())
                <p style="color:#7c8698;font-size:0.9rem;text-align:center;padding:1rem 0;">Belum ada tagihan IPL.</p>
            @else
            <div class="hidden md:block" style="overflow-x:auto;">
                <table style="width:100%;border-collapse:collapse;font-size:0.85rem;">
                    <thead>
                        <tr style="border-bottom:1px solid #f5f6f8;">
                            <th style="text-align:left;padding:8px 10px;color:#667085;font-weight:500;">Periode</th>
                            <th style="text-align:right;padding:8px 10px;color:#667085;font-weight:500;">Tagihan</th>
                            <th style="text-align:right;padding:8px 10px;color:#667085;font-weight:500;">Dibayar</th>
                            <th style="text-align:right;padding:8px 10px;color:#667085;font-weight:500;">Sisa</th>
                            <th style="text-align:center;padding:8px 10px;color:#667085;font-weight:500;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentBillings as $billing)
                        @php
                            $tagihan = ($billing->ipl_security_amount ?? 0) + ($billing->ipl_garbage_amount ?? 0) + ($billing->ipl_kas_rt_amount ?? 0);
                            $dibayar = ($billing->paid_security ?? 0) + ($billing->paid_garbage ?? 0) + ($billing->paid_kas_rt ?? 0);
                            $sisa    = $tagihan - $dibayar;
                        @endphp
                        <tr style="border-bottom:1px solid #ffffff;">
                            <td style="padding:8px 10px;color:#344054;">{{ $billing->period?->period_label ?? '—' }}</td>
                            <td style="padding:8px 10px;color:#344054;text-align:right;">Rp {{ number_format($tagihan, 0, ',', '.') }}</td>
                            <td style="padding:8px 10px;color:#12805c;text-align:right;">Rp {{ number_format($dibayar, 0, ',', '.') }}</td>
                            <td style="padding:8px 10px;text-align:right;color:{{ $sisa > 0 ? '#c77d1a' : '#12805c' }};">
                                Rp {{ number_format($sisa, 0, ',', '.') }}
                            </td>
                            <td style="padding:8px 10px;text-align:center;">
                                @if($sisa <= 0)
                                    <span style="background:#e3f1ea;color:#12805c;padding:2px 8px;border-radius:4px;font-size:0.75rem;">Lunas</span>
                                @elseif($dibayar > 0)
                                    <span style="background:#111827;color:#c77d1a;padding:2px 8px;border-radius:4px;font-size:0.75rem;">Sebagian</span>
                                @else
                                    <span style="background:#f7e7e4;color:#c0453b;padding:2px 8px;border-radius:4px;font-size:0.75rem;">Belum Bayar</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="md:hidden divide-y" style="border-color:#eef0f3;">
                @foreach($recentBillings as $billing)
                @php
                    $tagihan = ($billing->ipl_security_amount ?? 0) + ($billing->ipl_garbage_amount ?? 0) + ($billing->ipl_kas_rt_amount ?? 0);
                    $dibayar = ($billing->paid_security ?? 0) + ($billing->paid_garbage ?? 0) + ($billing->paid_kas_rt ?? 0);
                    $sisa    = $tagihan - $dibayar;
                @endphp
                <div class="py-3" wire:key="bill-{{ $billing->id }}">
                    <div class="flex items-center justify-between gap-2">
                        <span class="font-medium" style="color:#344054;">{{ $billing->period?->period_label ?? '—' }}</span>
                        @if($sisa <= 0)
                            <span class="shrink-0" style="background:#e3f1ea;color:#12805c;padding:2px 8px;border-radius:4px;font-size:0.75rem;">Lunas</span>
                        @elseif($dibayar > 0)
                            <span class="shrink-0" style="background:#111827;color:#c77d1a;padding:2px 8px;border-radius:4px;font-size:0.75rem;">Sebagian</span>
                        @else
                            <span class="shrink-0" style="background:#f7e7e4;color:#c0453b;padding:2px 8px;border-radius:4px;font-size:0.75rem;">Belum Bayar</span>
                        @endif
                    </div>
                    <div class="grid grid-cols-2 gap-x-4 gap-y-1 mt-2 text-xs">
                        <div><span class="block" style="color:#667085;">Tagihan</span><span style="color:#344054;">Rp {{ number_format($tagihan, 0, ',', '.') }}</span></div>
                        <div><span class="block" style="color:#667085;">Dibayar</span><span style="color:#12805c;">Rp {{ number_format($dibayar, 0, ',', '.') }}</span></div>
                        <div><span class="block" style="color:#667085;">Sisa</span><span style="color:{{ $sisa > 0 ? '#c77d1a' : '#12805c' }};">Rp {{ number_format($sisa, 0, ',', '.') }}</span></div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>
</div>
