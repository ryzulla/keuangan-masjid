<div wire:poll.5s="checkForActiveAlert">

<script>
(function() {
    if (window.__eAlertInit) return;
    window.__eAlertInit = true;

    var playing = false;
    var audioEl = null;
    var notifPerm = 'default';
    var _titleInterval = null;
    var _originalTitle = document.title;
    var pendingPlay = false;
    var userInteracted = false;

    function generateAlarmWav() {
        var sampleRate = 22050;
        var duration = 1.2;
        var samples = sampleRate * duration;
        var buffer = new ArrayBuffer(44 + samples * 2);
        var view = new DataView(buffer);

        function writeStr(offset, str) {
            for (var i = 0; i < str.length; i++) view.setUint8(offset + i, str.charCodeAt(i));
        }

        writeStr(0, 'RIFF');
        view.setUint32(4, 36 + samples * 2, true);
        writeStr(8, 'WAVE');
        writeStr(12, 'fmt ');
        view.setUint32(16, 16, true);
        view.setUint16(20, 1, true);
        view.setUint16(22, 1, true);
        view.setUint32(24, sampleRate, true);
        view.setUint32(32, 2, true);
        view.setUint16(34, 16, true);
        writeStr(36, 'data');
        view.setUint32(40, samples * 2, true);

        for (var i = 0; i < samples; i++) {
            var t = i / sampleRate;
            var freq = (t % 0.3 < 0.2) ? 880 : 660;
            var envelope = (t % 0.3 < 0.2) ? 0.8 : 0.5;
            var val = Math.sin(2 * Math.PI * freq * t) * envelope;
            val *= (1 - ((t * 10) % 1) * 0.3);
            view.setInt16(44 + i * 2, val * 32767, true);
        }

        var blob = new Blob([buffer], { type: 'audio/wav' });
        return URL.createObjectURL(blob);
    }

    function ensureAudio() {
        if (audioEl) return;
        audioEl = new Audio(generateAlarmWav());
        audioEl.loop = true;
        audioEl.preload = 'auto';
    }

    function requestNotifPerm() {
        if (!('Notification' in window)) return;
        if (Notification.permission === 'granted') { notifPerm = 'granted'; return; }
        if (Notification.permission !== 'denied') {
            Notification.requestPermission().then(function(p) { notifPerm = p; });
        } else {
            notifPerm = 'denied';
        }
    }

    function sendNotification() {
        if (notifPerm !== 'granted') return;
        try {
            new Notification('DARURAT', {
                body: 'Tombol darurat ditekan! Segera cek.',
                icon: '/favicon.ico',
                requireInteraction: true,
                tag: 'emergency-alert',
                renotify: true
            });
        } catch(e) {}
    }

    function startTitleFlash() {
        if (_titleInterval) return;
        _originalTitle = document.title;
        var on = true;
        _titleInterval = setInterval(function() {
            document.title = on ? '⚠️ DARURAT — Tombol Darurat Aktif!' : _originalTitle;
            on = !on;
        }, 800);
    }

    function stopTitleFlash() {
        if (_titleInterval) { clearInterval(_titleInterval); _titleInterval = null; }
        document.title = _originalTitle;
    }

    window.__eAlert = {
        play: function() {
            if (playing) return;
            playing = true;
            ensureAudio();
            audioEl.currentTime = 0;
            var p = audioEl.play();
            if (p !== undefined) {
                p.catch(function() {
                    if (!userInteracted) pendingPlay = true;
                });
            }
            sendNotification();
            startTitleFlash();
            window.dispatchEvent(new Event('alert-sound'));
        },
        stop: function() {
            playing = false;
            pendingPlay = false;
            if (audioEl) { audioEl.pause(); audioEl.currentTime = 0; }
            stopTitleFlash();
            window.dispatchEvent(new Event('alert-sound'));
        },
        isPlaying: function() { return playing; }
    };

    function onUserInteraction() {
        if (userInteracted) return;
        userInteracted = true;
        requestNotifPerm();
        if (pendingPlay && playing) {
            pendingPlay = false;
            ensureAudio();
            audioEl.currentTime = 0;
            audioEl.play().catch(function() {});
        }
        document.removeEventListener('pointerdown', onUserInteraction);
        document.removeEventListener('keydown', onUserInteraction);
    }

    document.addEventListener('pointerdown', onUserInteraction);
    document.addEventListener('keydown', onUserInteraction);

    function initLivewire() {
        if (typeof Livewire === 'undefined') {
            setTimeout(initLivewire, 100);
            return;
        }
        Livewire.on('alert-activated', function() {
            window.__eAlert.play();
        });
    }
    initLivewire();
})();
</script>

<div x-data="{ playing: false }"
     x-on:alert-sound.window="playing = window.__eAlert ? window.__eAlert.isPlaying() : false"
     x-init="$nextTick(() => { playing = window.__eAlert ? window.__eAlert.isPlaying() : false })">

@if($activeAlert && $showBanner)
<div class="fixed inset-0 flex items-center justify-center p-4"
    style="position:fixed !important;inset:0 !important;z-index:2147483647 !important;">

    <div class="absolute inset-0" style="background:rgba(139,47,30,0.95);backdrop-filter:blur(8px);"></div>

    <div class="relative z-10 w-full max-w-md text-center text-white">
        <div class="w-24 h-24 mx-auto rounded-full flex items-center justify-center mb-6"
            style="background:rgba(255,255,255,0.15);animation:pulse-emergency 1.5s infinite;">
            <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>

        <h1 class="text-3xl font-bold uppercase tracking-widest mb-2" style="font-family:'Fraunces',Georgia,serif;">DARURAT</h1>

        @if($isTriggeredByMe)
        <p class="text-sm mb-4" style="color:rgba(255,255,255,0.8);">Anda mengirim alert darurat. Menunggu respons pengurus.</p>
        @endif

        <div class="rounded-xl p-4 mb-6" style="background:rgba(255,255,255,0.15);border:1px solid rgba(255,255,255,0.3);">
            <p class="text-sm" style="color:rgba(255,255,255,0.9);">Blok</p>
            <p class="text-2xl font-bold mb-2">{{ $activeAlert->block_code }}</p>
            <p class="text-sm" style="color:rgba(255,255,255,0.8);">{{ $activeAlert->resident->name ?? 'Warga' }}</p>
            <p class="text-xs mt-1" style="color:rgba(255,255,255,0.6);">{{ $activeAlert->created_at->diffForHumans() }}</p>
        </div>

        <div class="flex flex-col gap-3">
            @if(!$isTriggeredByMe)
            <button type="button"
                x-on:click="if(window.__eAlert.isPlaying()){window.__eAlert.stop();playing=false;}else{window.__eAlert.play();playing=true;}"
                class="w-full py-3 rounded-xl font-bold text-sm uppercase tracking-wider transition-all"
                :style="playing ? 'background:#ffffff;color:#8B2F1E;' : 'background:rgba(255,255,255,0.15);color:#ffffff;border:2px solid rgba(255,255,255,0.5);'"
                x-text="playing ? 'Matikan Suara' : 'Aktifkan Suara'">
            </button>
            @endif

            <button wire:click="stopAlert" wire:loading.attr="disabled"
                x-on:click="if(window.__eAlert)window.__eAlert.stop();playing=false;"
                class="w-full py-4 rounded-xl font-bold text-lg uppercase tracking-wider transition-all"
                style="background:#ffffff;color:#8B2F1E;">
                <span wire:loading.remove wire:target="stopAlert">HENTIKAN ALERT</span>
                <span wire:loading wire:target="stopAlert">Memproses...</span>
            </button>

            <button wire:click="dismissBanner"
                x-on:click="if(window.__eAlert)window.__eAlert.stop();playing=false;"
                class="w-full py-3 rounded-xl font-medium text-sm"
                style="background:rgba(255,255,255,0.15);color:rgba(255,255,255,0.9);border:1px solid rgba(255,255,255,0.3);">
                Tutup Sementara
            </button>
        </div>

        <p class="text-xs mt-4" style="color:rgba(255,255,255,0.5);">Alert akan muncul kembali dalam 30 detik jika masih aktif</p>
    </div>

    <style>
        @keyframes pulse-emergency {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.05); opacity: 0.8; }
        }
    </style>
</div>
@endif

</div>
</div>
