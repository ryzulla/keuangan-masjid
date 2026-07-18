@props([
    'model',                       // nama properti Livewire (mis. "filterBlock")
    'options' => [],               // array/collection [value => label]
    'placeholder' => 'Pilih...',   // teks saat belum dipilih (= opsi "semua")
    'searchPlaceholder' => 'Cari...',
])

<div
    x-data="{
        open: false,
        search: '',
        value: $wire.get('{{ $model }}') ?? '',
        options: {{ \Illuminate\Support\Js::from(collect($options)->map(fn($v) => (string) $v)->toArray()) }},
        get items() {
            const s = this.search.toLowerCase().trim();
            const all = Object.entries(this.options);
            return s ? all.filter(([v, l]) => String(l).toLowerCase().includes(s)) : all;
        },
        get label() {
            return (this.value !== '' && this.value !== null && this.options[this.value] !== undefined)
                ? this.options[this.value] : @js($placeholder);
        },
        isSel(v) { return String(v) === String(this.value); },
        pick(v) { this.value = v; this.open = false; this.search = ''; $wire.set('{{ $model }}', v); },
        toggle() { this.open = !this.open; if (this.open) this.$nextTick(() => this.$refs.q?.focus()); },
    }"
    @click.outside="open = false"
    @keydown.escape="open = false"
    {{ $attributes->merge(['class' => 'relative']) }}
    style="min-width:170px;"
>
    <button type="button" @click="toggle()" class="pp-ss-btn" :class="open ? 'open' : ''">
        <span x-text="label" class="truncate" :style="(value === '' || value === null) ? 'color:#909A8F;' : 'color:#17231E;'"></span>
        <svg class="w-4 h-4 shrink-0 transition-transform" :class="open ? 'rotate-180' : ''" style="color:#909A8F;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>

    <div x-show="open" x-cloak
        x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
        class="absolute z-50 mt-1 w-full rounded-xl overflow-hidden"
        style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 12px 30px -12px rgba(22,74,64,0.45);min-width:210px;">
        <div style="padding:8px;border-bottom:1px solid #F1F3EC;">
            <div class="relative">
                <svg class="absolute left-2.5 top-2.5 w-4 h-4" style="color:#909A8F;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input x-ref="q" x-model="search" type="text" placeholder="{{ $searchPlaceholder }}"
                    style="width:100%;background:#F1F3EC;border:1px solid #E0DFD4;color:#17231E;border-radius:0.5rem;padding:0.45rem 0.6rem 0.45rem 2rem;font-size:0.85rem;outline:none;"
                    @keydown.enter.prevent="if(items.length) pick(items[0][0])">
            </div>
        </div>
        <div style="max-height:250px;overflow-y:auto;padding:4px;">
            {{-- opsi "semua" (kosongkan filter) --}}
            <button type="button" class="pp-ss-opt" :class="isSel('') ? 'sel' : ''" @click="pick('')" x-show="!search">{{ $placeholder }}</button>
            <template x-for="[v, l] in items" :key="v">
                <button type="button" class="pp-ss-opt" :class="isSel(v) ? 'sel' : ''" @click="pick(v)" x-text="l"></button>
            </template>
            <div x-show="items.length === 0" style="padding:0.7rem;color:#909A8F;font-size:0.8rem;text-align:center;">Tidak ditemukan</div>
        </div>
    </div>
</div>
