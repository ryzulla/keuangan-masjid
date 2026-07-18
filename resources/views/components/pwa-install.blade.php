<div x-data="pwaInstall()" x-init="init()" x-show="showBanner"
    x-cloak
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-y-4"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 translate-y-4"
    class="fixed bottom-20 sm:bottom-6 left-4 right-4 sm:left-auto sm:right-6 sm:w-80 z-50"
    style="pointer-events:auto;">

    <div class="rounded-2xl p-4 shadow-2xl" style="background:var(--pp-surface,#fff);border:1px solid var(--pp-line,#E0DFD4);box-shadow:0 12px 40px rgba(22,74,64,.18);">

        <div class="flex items-start gap-3">
            <div class="w-11 h-11 rounded-xl flex items-center justify-center shrink-0" style="background:var(--pp-brand,#164A40);">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="#F4EFE2" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold" style="color:var(--pp-ink,#17231E);">Install Portal Warga</p>
                <p class="text-xs mt-0.5" style="color:var(--pp-muted,#586359);line-height:1.5;">
                    <template x-if="isIOS">
                        <span>Tap <strong>Share</strong> lalu <strong>"Add to Home Screen"</strong> untuk install.</span>
                    </template>
                    <template x-if="!isIOS">
                        <span>Tap install untuk akses cepat dari home screen Anda.</span>
                    </template>
                </p>
            </div>
            <button @click="dismiss()" class="shrink-0 p-1 rounded-lg" style="color:var(--pp-faint,#909A8F);">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="flex gap-2 mt-3">
            <template x-if="!isIOS && deferredPrompt">
                <button @click="install()"
                    class="flex-1 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider transition-all"
                    style="background:var(--pp-brand,#164A40);color:var(--pp-cream,#F4EFE2);">
                    Install
                </button>
            </template>
            <template x-if="isIOS">
                <button @click="dismiss()"
                    class="flex-1 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider transition-all"
                    style="background:var(--pp-brand,#164A40);color:var(--pp-cream,#F4EFE2);">
                    Mengerti
                </button>
            </template>
            <button @click="dismiss()"
                class="py-2.5 px-4 rounded-xl text-xs font-medium transition-all"
                style="background:var(--pp-surface-2,#F2F5EE);color:var(--pp-muted,#586359);">
                Nanti Saja
            </button>
        </div>
    </div>
</div>

<script>
function pwaInstall() {
    return {
        showBanner: false,
        deferredPrompt: null,
        isIOS: false,
        isStandalone: false,

        init() {
            this.isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
            this.isStandalone = window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true;

            if (this.isStandalone) return;
            if (localStorage.getItem('pwa_install_dismissed') === '1') return;

            window.addEventListener('beforeinstallprompt', (e) => {
                e.preventDefault();
                this.deferredPrompt = e;
                setTimeout(() => { this.showBanner = true; }, 3000);
            });

            if (this.isIOS && !this.isStandalone) {
                setTimeout(() => { this.showBanner = true; }, 3000);
            }
        },

        async install() {
            if (!this.deferredPrompt) return;
            this.deferredPrompt.prompt();
            const { outcome } = await this.deferredPrompt.userChoice;
            this.deferredPrompt = null;
            this.showBanner = false;
            if (outcome === 'accepted') {
                localStorage.setItem('pwa_install_dismissed', '1');
            }
        },

        dismiss() {
            this.showBanner = false;
            localStorage.setItem('pwa_install_dismissed', '1');
        }
    };
}
</script>
