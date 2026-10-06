<x-filament-panels::page>
@php $sek = $this->getSekretariatData(); @endphp

<style>
/* ── Sembunyikan section foto tersembunyi dari Filament ── */
.fi-section:has([data-piket-upload]) {
    display: none !important;
}

/* ── Kamera UI ── */
#pk-viewfinder {
    width: 100%;
    aspect-ratio: 4/3;
    object-fit: cover;
    display: block;
    background: #0a0a0a;
}
#pk-result {
    width: 100%;
    display: block;
    background: #0a0a0a;
}
.pk-shutter-ring {
    width: 72px; height: 72px;
    border-radius: 50%;
    border: 4px solid rgba(255,255,255,0.9);
    display: flex; align-items: center; justify-content: center;
    cursor: pointer;
    transition: transform 0.1s, border-color 0.15s;
    flex-shrink: 0;
}
.pk-shutter-ring:active { transform: scale(0.91); border-color: #f97316; }
.pk-shutter-inner {
    width: 54px; height: 54px;
    border-radius: 50%;
    background: #fff;
    transition: background 0.1s;
}
.pk-shutter-ring:active .pk-shutter-inner { background: #f97316; }

/* flash animation */
@keyframes pk-flash { 0%{opacity:0} 20%{opacity:0.85} 100%{opacity:0} }
.pk-flash { animation: pk-flash 0.35s ease-out forwards; }
</style>

<div x-data="piketApp(@js($sek))" x-init="boot()">

{{-- ══ GPS Banner ══════════════════════════════════════════════════════════ --}}
<div class="mb-4 space-y-2">
    <div x-show="gps.s==='loading'"
         class="flex items-center gap-3 p-3.5 rounded-xl border border-blue-200 bg-blue-50 text-blue-700 text-sm font-medium">
        <svg class="animate-spin h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
        </svg>
        Mendeteksi lokasi GPS…
    </div>
    <div x-show="gps.s==='ok' && sek.aktif && gps.valid" x-cloak
         class="flex items-center gap-3 p-3.5 rounded-xl border border-green-200 bg-green-50 text-green-700 text-sm font-semibold">
        ✅ <span x-text="`Lokasi valid — ${gps.jarak} meter dari sekretariat.`"></span>
    </div>
    <div x-show="gps.s==='ok' && sek.aktif && !gps.valid" x-cloak
         class="flex items-start gap-3 p-3.5 rounded-xl border-2 border-red-400 bg-red-50 text-red-700 text-sm">
        🚫
        <div class="flex-1">
            <p class="font-bold" x-text="`Kamu berada ${gps.jarak} meter (maks. ${sek.radius} m).`"></p>
            <p class="text-xs mt-0.5">Pindah ke area sekretariat terlebih dahulu.</p>
        </div>
        <button @click="getGps()" class="shrink-0 px-3 py-1 rounded-lg border border-red-300 bg-white text-red-600 text-xs font-bold">🔄</button>
    </div>
    <div x-show="gps.s==='ok' && !sek.aktif" x-cloak
         class="flex items-center gap-3 p-3.5 rounded-xl border border-blue-200 bg-blue-50 text-blue-700 text-sm">
        📍 GPS terdeteksi — upload dari mana saja diizinkan.
    </div>
    <div x-show="gps.s==='denied'" x-cloak
         class="p-3.5 rounded-xl border border-yellow-300 bg-yellow-50 text-yellow-800 text-sm">
        ⚠️ <strong>Izin GPS ditolak.</strong> Aktifkan izin lokasi di pengaturan browser lalu muat ulang.
    </div>
    <div x-show="gps.s==='error'" x-cloak
         class="flex items-center gap-3 p-3.5 rounded-xl border border-red-200 bg-red-50 text-red-700 text-sm">
        ❌ GPS gagal.
        <button @click="getGps()" class="ml-auto px-3 py-1 rounded-lg bg-white border border-red-300 text-red-600 text-xs font-bold">Coba Lagi</button>
    </div>
</div>

{{-- ══ KAMERA BLOCK ═════════════════════════════════════════════════════════ --}}
<div class="mb-6 rounded-2xl overflow-hidden border border-gray-200 shadow-md bg-[#0d0d0d]">

    {{-- ── Header ─────────────────────────────────────────────────────────── --}}
    <div class="flex items-center justify-between px-5 py-3 bg-[#111] border-b border-white/10">
        <div class="flex items-center gap-2">
            <div class="w-7 h-7 rounded-full bg-orange-500/20 flex items-center justify-center">
                <svg class="w-3.5 h-3.5 text-orange-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <span class="text-white text-sm font-semibold">Kamera Bukti Piket</span>
        </div>
        <div class="flex items-center gap-2">
            {{-- Indikator GPS --}}
            <div class="flex items-center gap-1.5 text-xs font-medium"
                 :class="gps.s==='ok' ? 'text-green-400' : 'text-yellow-400'">
                <span class="w-1.5 h-1.5 rounded-full animate-pulse"
                      :class="gps.s==='ok' ? 'bg-green-400' : 'bg-yellow-400'"></span>
                <span x-text="gps.s==='ok' ? 'GPS' : 'GPS...'"></span>
            </div>
            {{-- Jam live --}}
            <span class="text-white/50 text-xs font-mono" x-text="clock"></span>
        </div>
    </div>

    {{-- ── Viewfinder (loading state) ──────────────────────────────────────── --}}
    <div x-show="!cam.aktif && !cam.preview" class="relative aspect-[4/3] flex flex-col items-center justify-center bg-[#0d0d0d]">
        <div x-show="cam.loading" class="text-center">
            <svg class="animate-spin w-10 h-10 text-white/40 mx-auto mb-3" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
            </svg>
            <p class="text-white/50 text-sm">Membuka kamera…</p>
        </div>
        <div x-show="!cam.loading && !cam.err" class="text-center">
            <div class="w-20 h-20 rounded-full bg-white/5 border border-white/10 flex items-center justify-center mx-auto mb-4">
                <svg class="w-9 h-9 text-white/30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <p class="text-white/60 font-medium text-sm">Kamera belum aktif</p>
            <button type="button" @click="bukaCam()"
                    class="mt-4 inline-flex items-center gap-2 bg-orange-500 hover:bg-orange-600 text-white text-sm font-bold px-6 py-2.5 rounded-xl transition-colors">
                Aktifkan Kamera
            </button>
        </div>
        <div x-show="cam.err" x-cloak class="text-center px-6">
            <p class="text-red-400 text-sm font-medium mb-3" x-text="cam.err"></p>
            <button type="button" @click="bukaCam()"
                    class="inline-flex items-center gap-2 bg-orange-500 hover:bg-orange-600 text-white text-sm font-bold px-5 py-2.5 rounded-xl transition-colors">
                Coba Lagi
            </button>
        </div>
    </div>

    {{-- ── Live video + overlay ─────────────────────────────────────────────── --}}
    <div x-show="cam.aktif && !cam.preview" x-cloak class="relative">

        {{-- Flash overlay --}}
        <div x-ref="flash" class="absolute inset-0 bg-white pointer-events-none z-20 opacity-0 rounded-none"></div>

        {{-- Video --}}
        <video x-ref="vid" id="pk-viewfinder" autoplay playsinline muted></video>

        {{-- Corner guides (viewfinder aesthetics) --}}
        <div class="absolute inset-0 pointer-events-none z-10">
            {{-- Corner brackets --}}
            <div class="absolute top-4 left-4 w-8 h-8 border-t-2 border-l-2 border-white/60 rounded-tl-sm"></div>
            <div class="absolute top-4 right-4 w-8 h-8 border-t-2 border-r-2 border-white/60 rounded-tr-sm"></div>
            <div class="absolute bottom-[80px] left-4 w-8 h-8 border-b-2 border-l-2 border-white/60 rounded-bl-sm"></div>
            <div class="absolute bottom-[80px] right-4 w-8 h-8 border-b-2 border-r-2 border-white/60 rounded-br-sm"></div>
        </div>

        {{-- Watermark preview bottom overlay --}}
        <div class="absolute bottom-0 left-0 right-0 pointer-events-none z-10"
             style="background: linear-gradient(to top, rgba(0,0,0,0.78) 0%, rgba(0,0,0,0.3) 60%, transparent 100%); padding: 56px 14px 14px;">
            <div class="text-white font-mono space-y-0.5">
                <p class="text-xs opacity-90 drop-shadow" x-text="wm.coords" x-show="wm.coords"></p>
                <p class="text-xs opacity-75 drop-shadow leading-tight" x-text="wm.alamat" x-show="wm.alamat"></p>
                <div class="flex items-center gap-3 mt-1">
                    <p class="text-xs opacity-85 drop-shadow" x-text="wm.tgl"></p>
                    <p class="text-xs opacity-85 drop-shadow font-bold" x-text="wm.jam"></p>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Kontrol kamera ───────────────────────────────────────────────────── --}}
    <div x-show="cam.aktif && !cam.preview" x-cloak
         class="flex items-center justify-between px-6 py-4 bg-[#111]">

        {{-- Flip kamera --}}
        <button type="button" @click="flipCam()"
                class="w-12 h-12 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center transition-colors text-white text-lg"
                title="Ganti kamera">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
        </button>

        {{-- Shutter button --}}
        <button type="button" @click="ambilFoto()"
                class="pk-shutter-ring" title="Ambil Foto">
            <div class="pk-shutter-inner"></div>
        </button>

        {{-- Tutup kamera --}}
        <button type="button" @click="tutupCam()"
                class="w-12 h-12 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center transition-colors text-white/70 text-lg"
                title="Tutup kamera">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    {{-- ── Preview hasil foto ───────────────────────────────────────────────── --}}
    <div x-show="cam.preview" x-cloak>
        <canvas x-ref="canvas" id="pk-result"></canvas>

        <div class="flex items-center gap-3 px-4 py-3.5 bg-[#111] border-t border-white/10">
            <div class="flex-1 flex items-center gap-2.5 bg-green-500/15 border border-green-500/30 text-green-400 font-semibold text-sm py-3 px-4 rounded-xl">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
                Foto siap — isi form lalu klik Buat
            </div>
            <button type="button" @click="ulangi()"
                    class="flex items-center gap-1.5 px-4 py-3 rounded-xl bg-white/10 hover:bg-white/20 text-white/70 text-sm font-medium transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Ulangi
            </button>
        </div>
    </div>

    {{-- Canvas tersembunyi untuk watermark full-res --}}
    <canvas x-ref="canvasH" class="hidden"></canvas>
</div>

{{-- ══ FORM FILAMENT ═══════════════════════════════════════════════════════ --}}
<div :class="blocked ? 'pointer-events-none opacity-40 select-none' : ''"
     class="transition-opacity duration-300">
    <template x-if="blocked">
        <div class="mb-4 p-3 rounded-xl border border-red-300 bg-red-50 text-red-700 text-xs font-bold text-center uppercase tracking-wider">
            🚫 Form dikunci — pindah ke sekretariat dulu
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

</div>{{-- /piketApp --}}

<script>
function piketApp(sek) {
    return {
        sek,
        gps:   { s: 'loading', lat: null, lng: null, jarak: null, valid: false },
        cam:   { aktif: false, preview: false, loading: false, err: null },
        wm:    { coords: '', alamat: '', tgl: '', jam: '' },
        clock: '',
        stream: null,
        face:   'environment',
        _tick:  null,

        get blocked() {
            if (!sek.aktif)              return false;
            if (this.gps.s === 'denied') return false;
            if (this.gps.s !== 'ok')     return true;
            return !this.gps.valid;
        },

        // ── Boot ─────────────────────────────────────────────────
        boot() {
            this.tickClock();
            this._tick = setInterval(() => this.tickClock(), 1000);
            this.getGps();
            // Otomatis buka kamera setelah halaman siap
            this.$nextTick(() => setTimeout(() => this.bukaCam(), 600));
        },

        destroy() {
            this.stopStream();
            clearInterval(this._tick);
        },

        // ── Clock ─────────────────────────────────────────────────
        tickClock() {
            const now = new Date(), p = n => String(n).padStart(2, '0');
            this.clock   = `${p(now.getHours())}:${p(now.getMinutes())}:${p(now.getSeconds())}`;
            this.wm.tgl  = `📅 ${now.toLocaleDateString('id-ID', { weekday:'long', day:'2-digit', month:'long', year:'numeric' })}`;
            this.wm.jam  = `🕐 ${p(now.getHours())}:${p(now.getMinutes())}:${p(now.getSeconds())} WIB`;
        },

        // ── GPS ───────────────────────────────────────────────────
        getGps() {
            if (!navigator.geolocation) { this.gps.s = 'denied'; return; }
            this.gps.s = 'loading';
            navigator.geolocation.getCurrentPosition(pos => this.onGps(pos),
                err => { this.gps.s = err.code === 1 ? 'denied' : 'error'; },
                { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 });
        },

        onGps(pos) {
            this.gps.lat = pos.coords.latitude;
            this.gps.lng = pos.coords.longitude;
            this.gps.s   = 'ok';
            this.wm.coords = `📍 ${this.gps.lat.toFixed(6)}, ${this.gps.lng.toFixed(6)}`;

            if (sek.aktif && sek.sekLat !== 0) {
                this.gps.jarak = Math.round(this.haversine(this.gps.lat, this.gps.lng, sek.sekLat, sek.sekLng));
                this.gps.valid = this.gps.jarak <= sek.radius;
            } else {
                this.gps.jarak = null; this.gps.valid = true;
            }

            this.$nextTick(() => {
                if (window.Livewire) window.Livewire.dispatch('geo-update', { latitude: this.gps.lat, longitude: this.gps.lng });
            });

            // Reverse geocoding
            fetch(`https://nominatim.openstreetmap.org/reverse?lat=${this.gps.lat}&lon=${this.gps.lng}&format=json&accept-language=id`)
                .then(r => r.json())
                .then(d => {
                    const a = d.address || {};
                    const parts = [a.road||a.pedestrian||'', a.suburb||a.village||a.neighbourhood||'', a.city||a.town||a.county||''].filter(Boolean);
                    if (parts.length) this.wm.alamat = `🏠 ${parts.join(', ')}`;
                }).catch(() => {});
        },

        haversine(la1,lo1,la2,lo2) {
            const R=6371000, r=d=>d*Math.PI/180, dL=r(la2-la1), dG=r(lo2-lo1);
            const a=Math.sin(dL/2)**2+Math.cos(r(la1))*Math.cos(r(la2))*Math.sin(dG/2)**2;
            return R*2*Math.atan2(Math.sqrt(a),Math.sqrt(1-a));
        },

        // ── Kamera ───────────────────────────────────────────────
        async bukaCam() {
            this.cam.loading = true; this.cam.err = null;
            const vid = this.$refs.vid;
            const tryOpen = async (constraints) => {
                this.stream = await navigator.mediaDevices.getUserMedia(constraints);
                vid.srcObject = this.stream;
                await vid.play();
                this.cam.aktif = true; this.cam.loading = false;
            };
            try {
                await tryOpen({ video:{ facingMode:{ideal:this.face}, width:{ideal:1920}, height:{ideal:1080} }, audio:false });
            } catch {
                try { await tryOpen({ video:true, audio:false }); }
                catch(e) { this.cam.loading=false; this.cam.err=`Kamera tidak bisa dibuka: ${e.message}. Berikan izin kamera di browser.`; }
            }
        },

        async flipCam() {
            this.face = this.face === 'environment' ? 'user' : 'environment';
            this.stopStream();
            this.cam.aktif = false;
            await this.bukaCam();
        },

        tutupCam() { this.stopStream(); this.cam.aktif = false; },
        stopStream() { if (this.stream) { this.stream.getTracks().forEach(t=>t.stop()); this.stream=null; } },

        // ── Ambil foto + watermark ────────────────────────────────
        ambilFoto() {
            const vid = this.$refs.vid;
            const hc  = this.$refs.canvasH;
            const W   = vid.videoWidth || 1280;
            const H   = vid.videoHeight || 720;

            hc.width = W; hc.height = H;
            const ctx = hc.getContext('2d');

            // Mirror jika kamera depan
            if (this.face === 'user') {
                ctx.save(); ctx.translate(W,0); ctx.scale(-1,1);
                ctx.drawImage(vid,0,0,W,H); ctx.restore();
            } else {
                ctx.drawImage(vid,0,0,W,H);
            }

            // Flash
            const fl = this.$refs.flash;
            if (fl) { fl.classList.add('pk-flash'); setTimeout(()=>fl.classList.remove('pk-flash'), 400); }

            // ── Watermark ─────────────────────────────────────────
            const now = new Date(), p = n => String(n).padStart(2,'0');
            const tgl = now.toLocaleDateString('id-ID',{weekday:'long',day:'2-digit',month:'long',year:'numeric'});
            const jam = `${p(now.getHours())}:${p(now.getMinutes())}:${p(now.getSeconds())} WIB`;
            const coordStr = this.gps.lat !== null
                ? `${this.gps.lat.toFixed(6)}, ${this.gps.lng.toFixed(6)}`
                : 'Lokasi tidak tersedia';
            const alamat = this.wm.alamat.replace('🏠 ','') || '';

            const lines = [
                { t: `📍  ${coordStr}`, bold: false },
                ...(alamat ? [{ t: `🏠  ${alamat}`, bold: false }] : []),
                { t: `📅  ${tgl}`, bold: false },
                { t: `🕐  ${jam}`, bold: true },
            ];

            const fz   = Math.max(20, Math.round(W * 0.023));
            const lh   = Math.round(fz * 1.6);
            const padV = Math.round(fz * 0.8);
            const padH = Math.round(fz * 0.85);
            const stripH = lh * lines.length + padV * 2;

            // Gradient strip
            const gy = H - stripH - fz * 1.5;
            const grad = ctx.createLinearGradient(0, gy, 0, H);
            grad.addColorStop(0,   'rgba(0,0,0,0)');
            grad.addColorStop(0.3, 'rgba(0,0,0,0.55)');
            grad.addColorStop(1,   'rgba(0,0,0,0.85)');
            ctx.fillStyle = grad;
            ctx.fillRect(0, gy, W, H - gy);

            // Teks
            lines.forEach((line, i) => {
                const y = H - stripH + padV + i * lh;
                const fw = line.bold ? 'bold' : '600';
                ctx.font = `${fw} ${fz}px "Segoe UI", Arial, sans-serif`;
                ctx.textBaseline = 'top';
                // Outline
                ctx.lineWidth = Math.max(3, fz * 0.2);
                ctx.strokeStyle = 'rgba(0,0,0,0.9)';
                ctx.lineJoin = 'round';
                ctx.strokeText(line.t, padH, y);
                // Fill
                ctx.fillStyle = line.bold ? '#FED7AA' : '#FFFFFF';
                ctx.fillText(line.t, padH, y);
            });

            // Brand pojok kanan atas
            const brandFz = Math.round(fz * 0.72);
            ctx.font = `bold ${brandFz}px "Segoe UI", Arial, sans-serif`;
            ctx.textBaseline = 'top';
            ctx.fillStyle = 'rgba(255,255,255,0.45)';
            const brand = 'UKM SENJA';
            const bW = ctx.measureText(brand).width;
            ctx.fillText(brand, W - bW - padH, padH);

            // Tampilkan di canvas preview
            const dc = this.$refs.canvas;
            dc.width = W; dc.height = H;
            dc.getContext('2d').drawImage(hc, 0, 0);

            this.stopStream();
            this.cam.aktif   = false;
            this.cam.preview = true;

            // Inject ke FilePond
            hc.toBlob(blob => {
                const file = new File([blob], `piket-${Date.now()}.jpg`, { type:'image/jpeg' });
                this.injectFP(file);
            }, 'image/jpeg', 0.93);
        },

        injectFP(file) {
            const go = () => {
                const inputs = document.querySelectorAll('input[type="file"]');
                for (const inp of inputs) {
                    if (window.FilePond) {
                        const fp = window.FilePond.find(inp);
                        if (fp) { fp.addFile(file); return true; }
                    }
                    try {
                        const dt = new DataTransfer(); dt.items.add(file); inp.files = dt.files;
                        inp.dispatchEvent(new Event('change',{bubbles:true})); return true;
                    } catch { continue; }
                }
                return false;
            };
            if (!go()) { setTimeout(go, 700); setTimeout(go, 1600); }
        },

        ulangi() {
            this.cam.preview = false; this.cam.err = null;
            const c = this.$refs.canvas;
            if (c) c.getContext('2d').clearRect(0,0,c.width,c.height);
            if (window.FilePond) {
                document.querySelectorAll('input[type="file"]').forEach(inp => {
                    const fp = window.FilePond.find(inp);
                    if (fp) fp.removeFiles();
                });
            }
            this.$nextTick(() => this.bukaCam());
        },
    };
}
</script>

</x-filament-panels::page>
