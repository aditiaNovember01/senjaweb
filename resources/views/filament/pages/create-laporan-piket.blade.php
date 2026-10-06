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

{{-- ── Kamera Custom dengan Watermark ──────────────────────────────────────── --}}
<div x-data="piketKamera()"
     x-init="initKamera()"
     class="mb-6 bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">

    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
        <div>
            <h3 class="font-semibold text-gray-800 text-sm flex items-center gap-2">
                📸 Kamera Bukti Piket
            </h3>
            <p class="text-xs text-gray-500 mt-0.5">Foto akan otomatis mendapat watermark lokasi, tanggal, dan jam</p>
        </div>
        {{-- Status indikator --}}
        <div x-show="fotoSiap" x-cloak
             class="flex items-center gap-1.5 text-xs font-semibold text-green-600 bg-green-50 border border-green-200 px-3 py-1 rounded-full">
            ✅ Foto siap
        </div>
    </div>

    <div class="p-5">

        {{-- Panel sebelum kamera dibuka --}}
        <div x-show="!kameraAktif && !fotoSiap" class="text-center py-8">
            <div class="w-16 h-16 rounded-full bg-orange-50 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <p class="text-gray-600 font-medium text-sm mb-1">Ambil foto bukti piket</p>
            <p class="text-gray-400 text-xs mb-5">Watermark lokasi + waktu akan ditambahkan otomatis</p>
            <button type="button"
                    @click="bukaKamera()"
                    class="inline-flex items-center gap-2 bg-orange-500 hover:bg-orange-600 text-white font-semibold text-sm px-6 py-3 rounded-xl transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Buka Kamera
            </button>

            <div x-show="kameraError" x-cloak
                 class="mt-4 p-3 rounded-xl bg-red-50 border border-red-200 text-red-600 text-xs" x-text="kameraError">
            </div>
        </div>

        {{-- Live viewfinder --}}
        <div x-show="kameraAktif && !fotoSiap" x-cloak class="space-y-3">
            <div class="relative rounded-xl overflow-hidden bg-black">
                <video x-ref="videoEl"
                       autoplay playsinline muted
                       class="w-full max-h-[60vh] object-contain block"></video>

                {{-- Watermark preview overlay (hanya tampilan, bukan gambar asli) --}}
                <div class="absolute bottom-0 left-0 right-0 bg-black/55 px-3 py-2 text-white text-xs font-mono space-y-0.5 pointer-events-none">
                    <div x-text="gpsText" class="opacity-90"></div>
                    <div x-text="tglJamText" class="opacity-90"></div>
                </div>
            </div>

            <div class="flex gap-3">
                <button type="button"
                        @click="ambilFoto()"
                        class="flex-1 flex items-center justify-center gap-2 bg-orange-500 hover:bg-orange-600 text-white font-bold text-sm py-3 rounded-xl transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Ambil Foto
                </button>
                <button type="button"
                        @click="ganti()"
                        class="px-4 py-3 rounded-xl border border-gray-200 text-gray-500 hover:bg-gray-50 text-sm font-medium transition-colors">
                    ✕ Tutup
                </button>
            </div>
        </div>

        {{-- Preview hasil foto + watermark --}}
        <div x-show="fotoSiap" x-cloak class="space-y-3">
            <div class="relative rounded-xl overflow-hidden bg-black">
                <canvas x-ref="canvasEl"
                        class="w-full max-h-[60vh] object-contain block"></canvas>
            </div>

            <div class="flex gap-3">
                <button type="button"
                        @click="ganti()"
                        class="flex-1 flex items-center justify-center gap-2 border border-gray-200 text-gray-600 hover:bg-gray-50 font-medium text-sm py-3 rounded-xl transition-colors">
                    🔄 Ulangi Foto
                </button>
                <div class="flex-1 flex items-center justify-center gap-2 bg-green-50 border border-green-200 text-green-700 font-semibold text-sm py-3 rounded-xl">
                    ✅ Foto berhasil
                </div>
            </div>
        </div>

        {{-- Canvas tersembunyi untuk proses watermark --}}
        <canvas x-ref="canvasHidden" class="hidden"></canvas>

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

</div>{{-- /x-data piketGeo --}}

<script>
// ── GPS + radius logic ─────────────────────────────────────────────────────
function piketGeo(cfg) {
    return {
        cfg,
        status:      'loading',
        latitude:    null,
        longitude:   null,
        jarak:       null,
        lokasiValid: false,

        get formBlocked() {
            if (!cfg.aktif)               return false;
            if (this.status === 'denied') return false;
            if (this.status !== 'ok')     return true;
            return !this.lokasiValid;
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

            // Kirim ke Livewire server
            this.$nextTick(() => {
                if (window.Livewire) {
                    window.Livewire.dispatch('geo-update', {
                        latitude:  this.latitude,
                        longitude: this.longitude,
                    });
                }
            });

            // Kasih info GPS ke komponen kamera
            window._piketGps = {
                lat: this.latitude,
                lng: this.longitude,
            };
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

// ── Kamera custom + Canvas watermark ─────────────────────────────────────────
function piketKamera() {
    return {
        kameraAktif: false,
        fotoSiap:    false,
        kameraError: null,
        stream:      null,
        gpsText:     'Lokasi: menunggu GPS...',
        tglJamText:  '',
        clockTimer:  null,

        initKamera() {
            // Update jam setiap detik
            this.updateJam();
            this.clockTimer = setInterval(() => this.updateJam(), 1000);
        },

        updateJam() {
            const now = new Date();
            const pad = n => String(n).padStart(2, '0');
            const tgl = now.toLocaleDateString('id-ID', { day:'2-digit', month:'long', year:'numeric' });
            const jam = `${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())} WIB`;
            this.tglJamText = `📅 ${tgl}  🕐 ${jam}`;

            const gps = window._piketGps;
            if (gps) {
                this.gpsText = `📍 ${gps.lat.toFixed(6)}, ${gps.lng.toFixed(6)}`;
            } else {
                this.gpsText = '📍 Lokasi: menunggu GPS...';
            }
        },

        async bukaKamera() {
            this.kameraError = null;
            try {
                // Coba kamera belakang dulu, fallback ke kamera manapun
                let constraints = { video: { facingMode: { ideal: 'environment' }, width: { ideal: 1280 }, height: { ideal: 960 } }, audio: false };
                try {
                    this.stream = await navigator.mediaDevices.getUserMedia(constraints);
                } catch {
                    this.stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: false });
                }
                this.$refs.videoEl.srcObject = this.stream;
                this.kameraAktif = true;
            } catch (err) {
                this.kameraError = `Kamera tidak bisa dibuka: ${err.message}. Pastikan izin kamera diberikan di browser.`;
            }
        },

        ambilFoto() {
            const video  = this.$refs.videoEl;
            const canvas = this.$refs.canvasEl;
            const hidden = this.$refs.canvasHidden;

            const W = video.videoWidth  || 1280;
            const H = video.videoHeight || 960;

            // Render frame video ke hidden canvas (full resolusi)
            hidden.width  = W;
            hidden.height = H;
            const hctx = hidden.getContext('2d');
            hctx.drawImage(video, 0, 0, W, H);

            // ── Watermark ──────────────────────────────────────────────────
            const now   = new Date();
            const pad   = n => String(n).padStart(2, '0');
            const tgl   = now.toLocaleDateString('id-ID', { weekday:'long', day:'2-digit', month:'long', year:'numeric' });
            const jam   = `${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())} WIB`;
            const gps   = window._piketGps;
            const lokasiStr = gps ? `${gps.lat.toFixed(6)}, ${gps.lng.toFixed(6)}` : 'Lokasi tidak tersedia';

            const lines = [
                `\uD83D\uDCCD ${lokasiStr}`,
                `\uD83D\uDCC5 ${tgl}`,
                `\uD83D\uDD50 ${jam}`,
            ];

            const fontSize  = Math.max(16, Math.round(W * 0.025));
            const lineH     = fontSize + 10;
            const stripH    = lineH * lines.length + 18;
            const stripY    = H - stripH;

            // Strip gelap semi-transparan
            hctx.fillStyle = 'rgba(0,0,0,0.58)';
            hctx.fillRect(0, stripY, W, stripH);

            // Teks putih
            hctx.font         = `bold ${fontSize}px "Arial", sans-serif`;
            hctx.textBaseline = 'top';

            lines.forEach((line, i) => {
                const y = stripY + 10 + i * lineH;
                // Shadow
                hctx.fillStyle = 'rgba(0,0,0,0.7)';
                hctx.fillText(line, 13, y + 1);
                // Teks utama
                hctx.fillStyle = '#ffffff';
                hctx.fillText(line, 12, y);
            });

            // Salin ke canvas tampil (bisa beda ukuran layar)
            canvas.width  = W;
            canvas.height = H;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(hidden, 0, 0);

            // Matikan stream kamera
            if (this.stream) {
                this.stream.getTracks().forEach(t => t.stop());
                this.stream = null;
            }
            this.kameraAktif = false;
            this.fotoSiap    = true;

            // ── Inject foto ke Filament FileUpload ────────────────────────
            hidden.toBlob(blob => {
                const file = new File([blob], `bukti-piket-${Date.now()}.jpg`, { type: 'image/jpeg' });
                this.injectKeFilament(file);
            }, 'image/jpeg', 0.92);
        },

        injectKeFilament(file) {
            // Cari semua input file di halaman (Filament FileUpload / Filepond)
            const injected = this.cariDanInject(file);
            if (!injected) {
                // Coba lagi setelah Livewire selesai render
                setTimeout(() => this.cariDanInject(file), 600);
            }
        },

        cariDanInject(file) {
            // FilePond menyimpan instance di elemen input
            const inputs = document.querySelectorAll('input[type="file"]');
            for (const input of inputs) {
                // Coba lewat FilePond API
                if (window.FilePond) {
                    const fp = window.FilePond.find(input);
                    if (fp) {
                        fp.addFile(file);
                        return true;
                    }
                }
                // Fallback: DataTransfer
                try {
                    const dt = new DataTransfer();
                    dt.items.add(file);
                    input.files = dt.files;
                    input.dispatchEvent(new Event('change', { bubbles: true }));
                    return true;
                } catch (e) { /* lanjut */ }
            }
            return false;
        },

        ganti() {
            // Reset
            if (this.stream) {
                this.stream.getTracks().forEach(t => t.stop());
                this.stream = null;
            }
            this.kameraAktif = false;
            this.fotoSiap    = false;
            this.kameraError = null;

            // Reset canvas
            const c = this.$refs.canvasEl;
            if (c) { const ctx = c.getContext('2d'); ctx.clearRect(0, 0, c.width, c.height); }

            // Reset FilePond / input file
            const inputs = document.querySelectorAll('input[type="file"]');
            for (const input of inputs) {
                if (window.FilePond) {
                    const fp = window.FilePond.find(input);
                    if (fp) { fp.removeFiles(); continue; }
                }
                input.value = '';
            }
        },

        destroy() {
            if (this.stream) this.stream.getTracks().forEach(t => t.stop());
            if (this.clockTimer) clearInterval(this.clockTimer);
        }
    };
}
</script>

</x-filament-panels::page>
