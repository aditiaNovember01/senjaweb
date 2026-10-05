<x-filament-panels::page>
@php $sek = $this->getSekretariatData(); @endphp

<div x-data="piketGeo(@js($sek))" x-init="init()">

{{-- ── GPS Status Banner ──────────────────────────────────────────────────── --}}
<div class="mb-5">

    <div x-show="status === 'loading'"
         class="flex items-center gap-3 p-4 rounded-xl border border-blue-200 bg-blue-50 text-blue-700 text-sm font-medium">
        <svg class="animate-spin h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
        </svg>
        Mendeteksi lokasi GPS kamu… (bisa memakan beberapa detik)
    </div>

    <div x-show="status === 'ok' && cfg.aktif && lokasiValid" x-cloak
         class="flex items-center gap-3 p-4 rounded-xl border border-green-200 bg-green-50 text-green-700 text-sm font-semibold">
        <span class="text-base">✅</span>
        <span x-text="`Lokasi valid — ${jarak} meter dari sekretariat (maks. ${cfg.radius} m). Silakan upload bukti.`"></span>
    </div>

    <div x-show="status === 'ok' && cfg.aktif && !lokasiValid" x-cloak
         class="flex items-start gap-3 p-4 rounded-xl border-2 border-red-400 bg-red-50 text-red-700 text-sm">
        <span class="text-lg flex-shrink-0 mt-0.5">🚫</span>
        <div class="flex-1">
            <p class="font-bold" x-text="`Kamu berada ${jarak} meter dari sekretariat — melewati batas ${cfg.radius} meter.`"></p>
            <p class="mt-1 text-red-600">Upload bukti tidak bisa dilakukan dari lokasi ini. Pindah ke area sekretariat terlebih dahulu.</p>
        </div>
        <button @click="retry()"
                class="flex-shrink-0 px-3 py-1.5 rounded-lg border border-red-300 bg-white text-red-600 text-xs font-bold hover:bg-red-100 transition">
            🔄 Perbarui
        </button>
    </div>

    <div x-show="status === 'ok' && !cfg.aktif" x-cloak
         class="flex items-center gap-3 p-4 rounded-xl border border-blue-200 bg-blue-50 text-blue-700 text-sm">
        📍 GPS terdeteksi. Validasi radius belum diaktifkan — upload dari mana saja diizinkan.
    </div>

    <div x-show="status === 'denied'" x-cloak
         class="flex items-start gap-3 p-4 rounded-xl border border-yellow-300 bg-yellow-50 text-yellow-800 text-sm">
        <span class="text-base flex-shrink-0">⚠️</span>
        <div>
            <p class="font-semibold">Izin lokasi ditolak oleh browser.</p>
            <p class="mt-0.5">Aktifkan izin GPS di pengaturan browser/HP, lalu muat ulang halaman.</p>
        </div>
    </div>

    <div x-show="status === 'error'" x-cloak
         class="flex items-center gap-3 p-4 rounded-xl border border-red-200 bg-red-50 text-red-700 text-sm">
        <span>❌ Gagal mendapatkan GPS. Pastikan GPS aktif.</span>
        <button @click="retry()"
                class="ml-auto px-3 py-1.5 rounded-lg bg-white border border-red-300 text-red-600 text-xs font-bold hover:bg-red-50 transition">
            Coba Lagi
        </button>
    </div>

</div>

{{-- ── Form — dikunci saat di luar radius ──────────────────────────────────── --}}
<div :class="formBlocked ? 'pointer-events-none opacity-40 select-none' : ''"
     class="transition-opacity duration-300">

    <template x-if="formBlocked">
        <div class="mb-4 p-3 rounded-lg border border-red-300 bg-red-50 text-red-700 text-xs font-bold text-center tracking-wide uppercase">
            🚫 Form dikunci — pindah ke sekretariat untuk membuka upload
        </div>
    </template>

    <x-filament-panels::form wire:submit="create">
        {{ $this->form }}

        <x-filament-panels::form.actions
            :actions="$this->getCachedFormActions()"
            :full-width="$this->hasFullWidthFormActions()"
        />
    </x-filament-panels::form>

</div>

</div>{{-- /x-data --}}

<script>
function piketGeo(cfg) {
    return {
        cfg,
        status:      'loading',
        latitude:    null,
        longitude:   null,
        jarak:       null,
        lokasiValid: false,

        get formBlocked() {
            if (!cfg.aktif)              return false;  // radius belum diset admin
            if (this.status === 'denied') return false; // GPS ditolak — tetap izinkan tanpa lokasi
            if (this.status !== 'ok')    return true;   // masih loading / error — block dulu
            return !this.lokasiValid;                   // di luar radius — block
        },

        init() { this.ambilLokasi(); },
        retry() { this.ambilLokasi(); },

        ambilLokasi() {
            if (!navigator.geolocation) { this.status = 'denied'; return; }
            this.status = 'loading';
            navigator.geolocation.getCurrentPosition(
                pos => this.onSuccess(pos),
                err => this.onError(err),
                { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 }
            );
        },

        onSuccess(pos) {
            this.latitude  = pos.coords.latitude;
            this.longitude = pos.coords.longitude;
            this.status    = 'ok';

            if (cfg.aktif && cfg.sekLat !== 0) {
                this.jarak       = Math.round(this.haversine(this.latitude, this.longitude, cfg.sekLat, cfg.sekLng));
                this.lokasiValid = this.jarak <= cfg.radius;
            } else {
                this.jarak       = null;
                this.lokasiValid = true;
            }

            // Kirim ke Livewire server supaya PHP bisa validasi ulang & simpan
            this.$nextTick(() => {
                if (window.Livewire) {
                    window.Livewire.dispatch('geo-update', {
                        latitude:  this.latitude,
                        longitude: this.longitude,
                    });
                }
            });
        },

        onError(err) {
            this.status = err.code === 1 ? 'denied' : 'error';
        },

        haversine(lat1, lng1, lat2, lng2) {
            const R   = 6371000;
            const rad = d => d * Math.PI / 180;
            const dL  = rad(lat2 - lat1);
            const dG  = rad(lng2 - lng1);
            const a   = Math.sin(dL/2)**2 + Math.cos(rad(lat1)) * Math.cos(rad(lat2)) * Math.sin(dG/2)**2;
            return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        }
    };
}
</script>

</x-filament-panels::page>
