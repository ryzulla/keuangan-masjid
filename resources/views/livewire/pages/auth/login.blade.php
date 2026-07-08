<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;

use function Livewire\Volt\form;
use function Livewire\Volt\layout;

layout('layouts.guest');

form(LoginForm::class);

$login = function () {
    $this->validate();

    $this->form->authenticate();

    Session::regenerate();

    $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
};

?>

<div>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="login" class="space-y-5">
        <div>
            <label for="email" class="block text-sm font-medium mb-1.5" style="color:#475467;">Email</label>
            <input wire:model="form.email" id="email" type="email" name="email" required autofocus autocomplete="username"
                class="w-full px-4 py-2.5 text-sm rounded-xl outline-none transition-colors"
                style="background:#ffffff;border:1px solid #e4e7ec;color:#1d2939;"
                onfocus="this.style.borderColor='#111827'" onblur="this.style.borderColor='#e4e7ec'">
            @if($errors->get('form.email'))
                <p class="mt-1.5 text-xs" style="color:#c0453b;">{{ collect($errors->get('form.email'))->first() }}</p>
            @endif
        </div>

        <div>
            <label for="password" class="block text-sm font-medium mb-1.5" style="color:#475467;">Password</label>
            <input wire:model="form.password" id="password" type="password" name="password" required autocomplete="current-password"
                class="w-full px-4 py-2.5 text-sm rounded-xl outline-none transition-colors"
                style="background:#ffffff;border:1px solid #e4e7ec;color:#1d2939;"
                onfocus="this.style.borderColor='#111827'" onblur="this.style.borderColor='#e4e7ec'">
            @if($errors->get('form.password'))
                <p class="mt-1.5 text-xs" style="color:#c0453b;">{{ collect($errors->get('form.password'))->first() }}</p>
            @endif
        </div>

        <div class="flex items-center justify-between">
            <label for="remember" class="inline-flex items-center gap-2 cursor-pointer select-none">
                <input wire:model="form.remember" id="remember" type="checkbox" name="remember"
                    class="w-4 h-4 rounded"
                    style="accent-color:#111827;">
                <span class="text-sm" style="color:#667085;">Ingat saya</span>
            </label>
            @if(Route::has('password.request'))
                <a href="{{ route('password.request') }}" wire:navigate
                    class="text-sm transition-colors"
                    style="color:#111827;"
                    onmouseover="this.style.color='#111827'" onmouseout="this.style.color='#111827'">
                    Lupa password?
                </a>
            @endif
        </div>

        <button type="submit"
            class="w-full py-3 rounded-xl text-sm font-bold tracking-wide transition-colors"
            style="background:#111827;color:#ffffff;"
            onmouseover="this.style.background='#1f2a37'" onmouseout="this.style.background='#1f2a37'"
            wire:loading.attr="disabled">
            <span wire:loading.remove>Masuk</span>
            <span wire:loading class="inline-flex items-center justify-center gap-2">
                <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                Memproses...
            </span>
        </button>
    </form>
</div>
