<x-filament-panels::page>

@if($isAnggota)
{{-- ═══════════════════════════════════════════════════════════════
     DASHBOARD ANGGOTA — tampilan terbatas
═══════════════════════════════════════════════════════════════ --}}
<style>
.senja-anggota-hero {
    background: linear-gradient(135deg, #EA580C 0%, #9333ea 100%);
    border-radius: 16px; padding: 28px 24px; color: white; margin-bottom: 24px; position: relative; overflow: hidden;
}
.senja-anggota-hero::after {
    content:''; position:absolute; right:-30px; top:-30px; width:160px; height:160px;
    background:rgba(255,255,255,0.07); border-radius:50%;
}
.senja-piket-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:16px; margin-bottom:24px; }
@media(max-width:640px){ .senja-piket-grid { grid-template-columns:1fr; } }
.senja-piket-stat { background:#fff; border:1px solid #f1f5f9; border-radius:14px; padding:18px 20px; }
.dark .senja-piket-stat { background:rgb(17 24 39); border-color:rgb(55 65 81); }
.senja-jadwal-list { background:#fff; border:1px solid #f1f5f9; border-radius:16px; overflow:hidden; margin-bottom:20px; }
.dark .senja-jadwal-list { background:rgb(17 24 39); border-color:rgb(55 65 81); }
.senja-jadwal-row { display:flex; align-items:center; gap:14px; padding:14px 20px; border-bottom:1px solid #f8fafc; transition:background 0.15s; }
.dark .senja-jadwal-row { border-color:rgb(55 65 81); }
.senja-jadwal-row:last-child { border-bottom:none; }
.senja-jadwal-row:hover { background:#f8fafc; }
.dark .senja-jadwal-row:hover { background:rgb(31 41 55); }
</style>

{{-- Hero --}}
<div class="senja-anggota-hero">
    <div style="position:relative;z-index:1">
        <p style="font-size:0.8rem;opacity:0.75;margin-bottom:4px">{{ now()->translatedFormat('l, d F Y') }}</p>
        <h1 style="font-size:1.35rem;font-weight:700;margin-bottom:4px">Hai, {{ auth()->user()->name }} 👋</h1>
        @if($anggota)
        <p style="font-size:0.8125rem;opacity:0.8">
            {{ $anggota->divisi->nama ?? 'Divisi —' }} &nbsp;•&nbsp; {{ $anggota->status }}
        </p>
        @endif
    </div>
</div>

{{-- Stat piket --}}
<div class="senja-piket-grid">
    <div class="senja-piket-stat">
        <div style="font-size:0.75rem;color:#64748b;margin-bottom:6px">Total Jadwal</div>
        <div style="font-size:2rem;font-weight:700;color:#0f172a">{{ $jadwalPiket->count() }}</div>
        <div style="font-size:0.75rem;color:#94a3b8">hari piket/minggu</div>
    </div>
    <div class="senja-piket-stat" style="border-color:#dcfce7">
        <div style="font-size:0.75rem;color:#64748b;margin-bottom:6px">Tepat Waktu</div>
        <div style="font-size:2rem;font-weight:700;color:#16a34a">{{ $tepatWaktu }}</div>
        <div style="font-size:0.75rem;color:#94a3b8">dari {{ $sudahUpload }} upload</div>
    </div>
    <div class="senja-piket-stat" style="border-color:{{ $terlambat > 0 ? '#fee2e2' : '#f1f5f9' }}">
        <div style="font-size:0.75rem;color:#64748b;margin-bottom:6px">Terlambat</div>
        <div style="font-size:2rem;font-weight:700;color:{{ $terlambat > 0 ? '#dc2626' : '#64748b' }}">{{ $terlambat }}</div>
        <div style="font-size:0.75rem;color:#94a3b8">kali keterlambatan</div>
    </div>
</div>

{{-- Info Keanggotaan + Radius --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px">

    {{-- Kartu Profil Anggota --}}
    <div class="senja-piket-stat" style="padding:20px">
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px">
            <div style="width:44px;height:44px;border-radius:50%;background:linear-gradient(135deg,#EA580C,#9333ea);display:flex;align-items:center;justify-content:center;font-size:1.1rem;font-weight:700;color:white;flex-shrink:0">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div>
                <div style="font-size:0.875rem;font-weight:700;color:#0f172a">{{ auth()->user()->name }}</div>
                <div style="font-size:0.75rem;color:#64748b">{{ auth()->user()->email }}</div>
            </div>
        </div>
        @if($anggota)
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px">
            <div style="background:#f8fafc;border-radius:8px;padding:8px 10px">
                <div style="font-size:0.65rem;color:#94a3b8;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:2px">NIM</div>
                <div style="font-size:0.8rem;font-weight:600;color:#0f172a">{{ $anggota->nim }}</div>
            </div>
            <div style="background:#f8fafc;border-radius:8px;padding:8px 10px">
                <div style="font-size:0.65rem;color:#94a3b8;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:2px">Divisi</div>
                <div style="font-size:0.8rem;font-weight:600;color:#0f172a">{{ $anggota->divisi->nama ?? '—' }}</div>
            </div>
            <div style="background:#f8fafc;border-radius:8px;padding:8px 10px">
                <div style="font-size:0.65rem;color:#94a3b8;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:2px">Angkatan</div>
                <div style="font-size:0.8rem;font-weight:600;color:#0f172a">{{ $anggota->angkatan->nama ?? '—' }}</div>
            </div>
            <div style="background:#f8fafc;border-radius:8px;padding:8px 10px">
                <div style="font-size:0.65rem;color:#94a3b8;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:2px">Status</div>
                <div style="font-size:0.75rem;font-weight:600;padding:2px 8px;border-radius:99px;display:inline-block;
                    background:{{ $anggota->status === 'Anggota Aktif' ? '#dcfce7' : '#f1f5f9' }};
                    color:{{ $anggota->status === 'Anggota Aktif' ? '#15803d' : '#475569' }}">
                    {{ $anggota->status }}
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- Info Radius Piket --}}
    <div x-data="radiusChecker(@js($infoRadius))"
         x-init="init()"
         class="senja-piket-stat" style="padding:20px">
        <div style="font-size:0.8125rem;font-weight:700;color:#0f172a;margin-bottom:12px;display:flex;align-items:center;gap:6px">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#EA580C" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Info Radius Piket
        </div>

        {{-- Sekretariat --}}
        <div style="font-size:0.75rem;color:#64748b;margin-bottom:10px">
            📍 {{ $infoRadius['sekretariat'] }}
        </div>

        @if($infoRadius['aktif'])
        {{-- Radius info --}}
        <div style="background:#fff7ed;border:1px solid #fed7aa;border-radius:10px;padding:10px 12px;margin-bottom:12px">
            <div style="font-size:0.75rem;color:#92400e;font-weight:600">
                Radius upload bukti: <span style="font-size:1rem;color:#EA580C">{{ $infoRadius['radius'] }} meter</span>
            </div>
            <div style="font-size:0.7rem;color:#b45309;margin-top:3px">
                Kamu harus berada dalam radius ini dari sekretariat saat mengupload bukti piket.
            </div>
        </div>

        {{-- Live distance --}}
        <div style="margin-bottom:8px">
            <div x-show="geoStatus === 'loading'"
                 style="font-size:0.75rem;color:#3b82f6;display:flex;align-items:center;gap:6px">
                <svg class="animate-spin" width="12" height="12" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                </svg>
                Mengukur jarak kamu...
            </div>
            <div x-show="geoStatus === 'ok'" x-cloak>
                <div :style="`font-size:0.8rem;font-weight:700;color:${dalamRadius ? '#15803d' : '#dc2626'}`"
                     x-text="`📏 Jarakmu: ${jarakMeter} meter dari sekretariat`"></div>
                <div :style="`font-size:0.7rem;margin-top:3px;color:${dalamRadius ? '#16a34a' : '#ef4444'}`"
                     x-text="dalamRadius ? '✅ Dalam radius — bisa upload piket' : '❌ Di luar radius — pindah ke sekretariat dulu'">
                </div>
                {{-- Progress bar jarak --}}
                <div style="margin-top:8px;background:#f1f5f9;border-radius:99px;height:6px;overflow:hidden">
                    <div :style="`height:100%;border-radius:99px;transition:width 0.5s ease;background:${dalamRadius?'#22c55e':'#ef4444'};width:${Math.min(100, (jarakMeter / cfg.radius) * 100)}%`"></div>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:0.65rem;color:#94a3b8;margin-top:3px">
                    <span>0 m</span>
                    <span>{{ $infoRadius['radius'] }} m (batas)</span>
                </div>
            </div>
            <div x-show="geoStatus === 'denied'" x-cloak
                 style="font-size:0.7rem;color:#b45309">
                ⚠️ Izin GPS ditolak — aktifkan di pengaturan browser.
            </div>
            <div x-show="geoStatus === 'error'" x-cloak
                 style="font-size:0.7rem;color:#dc2626;display:flex;align-items:center;gap:6px">
                ❌ Gagal GPS.
                <button @click="retry()" style="text-decoration:underline;font-weight:600;background:none;border:none;cursor:pointer;color:#dc2626;font-size:0.7rem">Coba lagi</button>
            </div>
        </div>
        @else
        <div style="background:#f1f5f9;border-radius:10px;padding:10px 12px;font-size:0.75rem;color:#64748b">
            Validasi radius belum diaktifkan oleh admin. Upload bukti bisa dari mana saja.
        </div>
        @endif

        {{-- Denda --}}
        @if($totalDendaBelumBayar > 0)
        <div style="margin-top:12px;background:#fee2e2;border:1px solid #fecaca;border-radius:10px;padding:10px 12px">
            <div style="font-size:0.75rem;font-weight:700;color:#dc2626">
                💸 Denda belum lunas: Rp {{ number_format($totalDendaBelumBayar, 0, ',', '.') }}
            </div>
            <div style="font-size:0.7rem;color:#b91c1c;margin-top:2px">Hubungi pengurus untuk penyelesaian denda.</div>
        </div>
        @endif
    </div>

</div>

{{-- Riwayat Denda (jika ada) --}}
@if($dendaAnggota->isNotEmpty())
<div class="senja-jadwal-list" style="margin-bottom:20px">
    <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 20px;border-bottom:1px solid #f1f5f9">
        <div style="font-size:0.875rem;font-weight:600;color:#0f172a;display:flex;align-items:center;gap:6px">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#dc2626" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Riwayat Denda Piket
        </div>
        <a href="{{ \App\Filament\Resources\LaporanPiketResource::getUrl('index') }}"
           style="font-size:0.75rem;color:#EA580C;font-weight:500;text-decoration:none">Laporan →</a>
    </div>
    @foreach($dendaAnggota as $denda)
    <div class="senja-jadwal-row">
        <div style="width:36px;height:36px;border-radius:8px;background:{{ $denda->sudah_dibayar ? '#dcfce7' : '#fee2e2' }};display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:1rem">
            {{ $denda->sudah_dibayar ? '✅' : '💸' }}
        </div>
        <div style="flex:1;min-width:0">
            <div style="font-size:0.8125rem;font-weight:500;color:#0f172a">{{ $denda->alasan }}</div>
            <div style="font-size:0.75rem;color:#64748b">{{ $denda->tanggal_piket->translatedFormat('l, d F Y') }}</div>
        </div>
        <div style="text-align:right;flex-shrink:0">
            <div style="font-size:0.8125rem;font-weight:700;color:{{ $denda->sudah_dibayar ? '#15803d' : '#dc2626' }}">
                Rp {{ number_format($denda->nominal, 0, ',', '.') }}
            </div>
            <span style="font-size:0.65rem;padding:2px 7px;border-radius:99px;font-weight:600;
                background:{{ $denda->sudah_dibayar ? '#dcfce7' : '#fee2e2' }};
                color:{{ $denda->sudah_dibayar ? '#15803d' : '#dc2626' }}">
                {{ $denda->sudah_dibayar ? 'Lunas' : 'Belum Bayar' }}
            </span>
        </div>
    </div>
    @endforeach
</div>
@endif

<script>
function radiusChecker(cfg) {
    return {
        cfg,
        geoStatus:   'loading',
        jarakMeter:  null,
        dalamRadius: false,

        init() { this.ambilLokasi(); },
        retry() { this.ambilLokasi(); },

        ambilLokasi() {
            if (!cfg.aktif) { this.geoStatus = 'ok'; this.dalamRadius = true; return; }
            if (!navigator.geolocation) { this.geoStatus = 'denied'; return; }
            this.geoStatus = 'loading';
            navigator.geolocation.getCurrentPosition(
                pos => {
                    const jarak = this.haversine(
                        pos.coords.latitude, pos.coords.longitude,
                        {{ (float) \App\Models\SiteSetting::get('piket_lat', '0') }},
                        {{ (float) \App\Models\SiteSetting::get('piket_lng', '0') }}
                    );
                    this.jarakMeter  = Math.round(jarak);
                    this.dalamRadius = this.jarakMeter <= cfg.radius;
                    this.geoStatus   = 'ok';
                },
                err => { this.geoStatus = err.code === 1 ? 'denied' : 'error'; },
                { enableHighAccuracy: true, timeout: 12000, maximumAge: 30000 }
            );
        },

        haversine(lat1, lng1, lat2, lng2) {
            const R   = 6371000;
            const rad = d => d * Math.PI / 180;
            const dL  = rad(lat2 - lat1), dG = rad(lng2 - lng1);
            const a   = Math.sin(dL/2)**2 + Math.cos(rad(lat1))*Math.cos(rad(lat2))*Math.sin(dG/2)**2;
            return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        }
    };
}
</script>

{{-- Jadwal piket minggu ini --}}
<div class="senja-jadwal-list">
    <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 20px;border-bottom:1px solid #f1f5f9">
        <div style="font-size:0.875rem;font-weight:600;color:#0f172a">Jadwal Piket Saya</div>
        <a href="{{ \App\Filament\Resources\JadwalPiketResource::getUrl('index') }}"
           style="font-size:0.75rem;color:#EA580C;font-weight:500;text-decoration:none">Lihat semua →</a>
    </div>
    @forelse($jadwalPiket as $jadwal)
    @php
        $anggotaIdVar  = auth()->user()->anggota_id;
        $hariIniNum    = now()->dayOfWeek;
        $isHariIni     = $jadwal->hari_ke === $hariIniNum;
        // Cek laporan hari ini dari relasi yang sudah di-eager-load
        $laporanHariIni = $jadwal->laporanPikets->first();
        $sudahLapor     = $laporanHariIni !== null;

        if (! $isHariIni) {
            $statusSaya = '—';
        } elseif ($sudahLapor) {
            $statusSaya = $laporanHariIni->status_kehadiran;
        } elseif ($jadwal->isDeadlineLewat($today)) {
            $statusSaya = 'Tidak Hadir';
        } else {
            $statusSaya = 'Belum Upload';
        }

        $badgeColor = match($statusSaya) {
            'Tepat Waktu'  => '#dcfce7;color:#15803d',
            'Terlambat'    => '#fef9c3;color:#854d0e',
            'Tidak Hadir'  => '#fee2e2;color:#dc2626',
            'Belum Upload' => '#dbeafe;color:#1d4ed8',
            default        => '#f1f5f9;color:#475569',
        };

        $hariLabel = \App\Models\JadwalPiket::$hariOptions[$jadwal->hari_ke] ?? "Hari {$jadwal->hari_ke}";
    @endphp
    <div class="senja-jadwal-row" style="{{ $isHariIni ? 'background:#fff7ed;border-left:3px solid #EA580C;' : '' }}">
        {{-- Ikon hari --}}
        <div style="width:44px;height:44px;border-radius:10px;background:{{ $isHariIni ? '#fff0e6' : '#f0f2f8' }};display:flex;flex-direction:column;align-items:center;justify-content:center;flex-shrink:0;text-align:center">
            <div style="font-size:0.75rem;font-weight:700;color:{{ $isHariIni ? '#EA580C' : '#64748b' }};line-height:1.2">{{ Str::upper(Str::substr($hariLabel,0,3)) }}</div>
        </div>
        <div style="flex:1;min-width:0">
            <div style="font-size:0.875rem;font-weight:600;color:#0f172a">
                Piket {{ $hariLabel }}
                @if($isHariIni)<span style="font-size:0.7rem;background:#FEF3C7;color:#92400E;padding:1px 6px;border-radius:99px;margin-left:4px">Hari ini</span>@endif
            </div>
            <div style="font-size:0.75rem;color:#64748b">Batas upload: {{ $jadwal->batas_upload }} &nbsp;•&nbsp; Mulai: {{ $jadwal->jam_mulai }}</div>
        </div>
        <div style="display:flex;flex-direction:column;align-items:flex-end;gap:4px;flex-shrink:0">
            @if($isHariIni)
                <span style="font-size:0.6875rem;padding:2px 8px;border-radius:99px;font-weight:600;background:{{ $badgeColor }}">
                    {{ $statusSaya }}
                </span>
                @if(! $sudahLapor && ! $jadwal->isDeadlineLewat($today))
                <a href="{{ \App\Filament\Resources\LaporanPiketResource::getUrl('create', ['jadwal_piket_id' => $jadwal->id]) }}"
                   style="font-size:0.7rem;color:#EA580C;font-weight:600;text-decoration:none;padding:3px 8px;border:1px solid #fed7aa;border-radius:6px;background:#fff7ed">
                    📷 Upload Bukti
                </a>
                @endif
            @else
                <span style="font-size:0.6875rem;color:#94a3b8">Rutin setiap {{ $hariLabel }}</span>
            @endif
        </div>
    </div>
    @empty
    <div style="padding:40px 20px;text-align:center;color:#94a3b8;font-size:0.875rem">
        Belum ada jadwal piket yang ditugaskan.
    </div>
    @endforelse
</div>

{{-- Kegiatan mendatang (read-only) --}}
@if($kegiatanMendatang->isNotEmpty())
<div class="senja-jadwal-list">
    <div style="padding:14px 20px;border-bottom:1px solid #f1f5f9">
        <div style="font-size:0.875rem;font-weight:600;color:#0f172a">Kegiatan Mendatang</div>
    </div>
    @foreach($kegiatanMendatang as $kegiatan)
    <div class="senja-jadwal-row">
        <div style="width:44px;height:44px;border-radius:10px;background:#eff6ff;display:flex;flex-direction:column;align-items:center;justify-content:center;flex-shrink:0;text-align:center">
            <div style="font-size:0.9rem;font-weight:700;color:#2563eb;line-height:1">{{ $kegiatan->tanggal_mulai->format('d') }}</div>
            <div style="font-size:0.65rem;color:#64748b">{{ $kegiatan->tanggal_mulai->format('M') }}</div>
        </div>
        <div style="flex:1;min-width:0">
            <div style="font-size:0.875rem;font-weight:500;color:#0f172a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $kegiatan->nama }}</div>
            <div style="font-size:0.75rem;color:#64748b">{{ $kegiatan->divisi->nama ?? '—' }} &bull; {{ $kegiatan->lokasi ?? '—' }}</div>
        </div>
        @php
            $kcol = $kegiatan->status === 'Sedang Berlangsung' ? '#dcfce7;color:#15803d' : '#dbeafe;color:#1d4ed8';
        @endphp
        <span style="font-size:0.65rem;padding:2px 8px;border-radius:99px;font-weight:600;background:{{ $kcol }};flex-shrink:0">
            {{ $kegiatan->status }}
        </span>
    </div>
    @endforeach
</div>
@endif

@else
{{-- ═══════════════════════════════════════════════════════════════
     DASHBOARD ADMIN / KETUA / SEKRETARIS — tampilan penuh
═══════════════════════════════════════════════════════════════ --}}

<style>
.senja-greeting {
    background: linear-gradient(135deg, #EA580C 0%, #c94a09 100%);
    border-radius: 16px;
    padding: 24px;
    color: white;
    position: relative;
    overflow: hidden;
    margin-bottom: 24px;
}
.senja-greeting::after {
    content: '';
    position: absolute;
    right: -30px;
    top: -30px;
    width: 160px;
    height: 160px;
    background: rgba(255,255,255,0.08);
    border-radius: 50%;
}
.senja-greeting::before {
    content: '';
    position: absolute;
    right: 60px;
    bottom: -40px;
    width: 100px;
    height: 100px;
    background: rgba(255,255,255,0.05);
    border-radius: 50%;
}
.senja-stat-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
    margin-bottom: 24px;
}
@media (min-width: 1024px) {
    .senja-stat-grid { grid-template-columns: repeat(3, 1fr); }
}
.senja-card {
    background: var(--color-white, #fff);
    border: 1px solid #f1f5f9;
    border-radius: 16px;
    padding: 20px;
    transition: all 0.2s ease;
    text-decoration: none;
    display: block;
}
.dark .senja-card {
    background: rgb(17 24 39);
    border-color: rgb(55 65 81);
}
.senja-card:hover {
    border-color: #fecda5;
    box-shadow: 0 4px 20px rgba(234,88,12,0.08);
    transform: translateY(-1px);
}
.senja-card-danger {
    background: #fff7f5;
    border-color: #fecda5;
}
.senja-card-danger:hover { background: #fff0eb; }
.senja-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 16px;
}
.senja-stat-num {
    font-size: 2rem;
    font-weight: 700;
    line-height: 1;
    color: #0f172a;
    margin-bottom: 4px;
}
.dark .senja-stat-num { color: #f8fafc; }
.senja-stat-label {
    font-size: 0.8125rem;
    color: #64748b;
    margin-bottom: 4px;
}
.senja-stat-sub {
    font-size: 0.75rem;
    color: #94a3b8;
}
.senja-badge {
    font-size: 0.6875rem;
    padding: 2px 8px;
    border-radius: 99px;
    font-weight: 600;
}
.senja-main-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 20px;
    margin-bottom: 20px;
}
@media (min-width: 1024px) {
    .senja-main-grid { grid-template-columns: 2fr 1fr; }
    .senja-bottom-grid { grid-template-columns: 1fr 1fr; }
}
.senja-bottom-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 20px;
    margin-bottom: 20px;
}
.senja-panel {
    background: var(--color-white, #fff);
    border: 1px solid #f1f5f9;
    border-radius: 16px;
    overflow: hidden;
}
.dark .senja-panel {
    background: rgb(17 24 39);
    border-color: rgb(55 65 81);
}
.senja-panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 20px;
    border-bottom: 1px solid #f8fafc;
}
.dark .senja-panel-header { border-color: rgb(55 65 81); }
.senja-panel-title {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 0.875rem;
    font-weight: 600;
    color: #0f172a;
}
.dark .senja-panel-title { color: #f8fafc; }
.senja-panel-icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.senja-panel-link {
    font-size: 0.75rem;
    color: #EA580C;
    font-weight: 500;
    text-decoration: none;
}
.senja-panel-link:hover { color: #c94a09; }
.senja-row {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 20px;
    border-bottom: 1px solid #f8fafc;
    transition: background 0.15s;
}
.dark .senja-row { border-color: rgb(55 65 81); }
.senja-row:last-child { border-bottom: none; }
.senja-row:hover { background: #f8fafc; }
.dark .senja-row:hover { background: rgb(31 41 55); }
.senja-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.875rem;
    flex-shrink: 0;
    background: #fff4ee;
    color: #EA580C;
}
.senja-empty {
    padding: 40px 20px;
    text-align: center;
    color: #94a3b8;
    font-size: 0.875rem;
}
.senja-progress-bar {
    width: 100%;
    background: #f1f5f9;
    border-radius: 99px;
    height: 6px;
    overflow: hidden;
    margin-top: 4px;
}
.dark .senja-progress-bar { background: rgb(55 65 81); }
.senja-quick-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
}
@media (min-width: 640px) { .senja-quick-grid { grid-template-columns: repeat(4, 1fr); } }
.senja-quick-btn {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 14px;
    border-radius: 12px;
    font-size: 0.8125rem;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.15s;
}
.senja-quick-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: rgba(255,255,255,0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    box-shadow: 0 1px 3px rgba(0,0,0,0.08);
}
.senja-cal-box {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: #f0f2f8;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    text-align: center;
}
</style>

{{-- Greeting --}}
<div class="senja-greeting">
    <div style="position:relative;z-index:1">
        <p style="font-size:0.8125rem;opacity:0.8;margin-bottom:4px">
            {{ now()->translatedFormat('l, d F Y') }}
        </p>
        <h1 style="font-size:1.375rem;font-weight:700;margin-bottom:4px">
            Selamat Datang, {{ auth()->user()->name }} 👋
        </h1>
        <p style="font-size:0.8125rem;opacity:0.75">
            @if($periodeAktif)
                Periode aktif: <strong>{{ $periodeAktif->nama }}</strong> &nbsp;•&nbsp; Sedang Aktif
            @else
                Belum ada periode kepengurusan aktif.
            @endif
        </p>
    </div>
</div>

{{-- Stats --}}
<div class="senja-stat-grid">

    {{-- Anggota Aktif --}}
    <a href="{{ \App\Filament\Resources\AnggotaResource::getUrl('index') }}" class="senja-card">
        <div style="display:flex;justify-content:space-between;align-items:flex-start">
            <div class="senja-icon" style="background:#dcfce7">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#16a34a" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <span class="senja-badge" style="background:#dcfce7;color:#15803d">Aktif</span>
        </div>
        <div class="senja-stat-num">{{ $stats['anggota_aktif'] }}</div>
        <div class="senja-stat-label">Anggota Aktif</div>
        <div class="senja-stat-sub">dari {{ $stats['total_anggota'] }} total anggota</div>
    </a>

    {{-- Surat Masuk --}}
    <a href="{{ \App\Filament\Resources\SuratMasukResource::getUrl('index') }}" class="senja-card">
        <div style="display:flex;justify-content:space-between;align-items:flex-start">
            <div class="senja-icon" style="background:#dbeafe">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#2563eb" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
            </div>
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#cbd5e1" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        </div>
        <div class="senja-stat-num">{{ $stats['surat_masuk'] }}</div>
        <div class="senja-stat-label">Surat Masuk</div>
        <div class="senja-stat-sub">total terarsip</div>
    </a>

    {{-- Surat Keluar --}}
    <a href="{{ \App\Filament\Resources\SuratKeluarResource::getUrl('index') }}" class="senja-card">
        <div style="display:flex;justify-content:space-between;align-items:flex-start">
            <div class="senja-icon" style="background:#ffedd5">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#EA580C" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
            </div>
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#cbd5e1" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        </div>
        <div class="senja-stat-num">{{ $stats['surat_keluar'] }}</div>
        <div class="senja-stat-label">Surat Keluar</div>
        <div class="senja-stat-sub">total terarsip</div>
    </a>

    {{-- Proker Aktif --}}
    <a href="{{ \App\Filament\Resources\ProgramKerjaResource::getUrl('index') }}" class="senja-card">
        <div style="display:flex;justify-content:space-between;align-items:flex-start">
            <div class="senja-icon" style="background:#f3e8ff">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#9333ea" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
            </div>
            <span class="senja-badge" style="background:#f3e8ff;color:#7e22ce">Berjalan</span>
        </div>
        <div class="senja-stat-num">{{ $stats['proker_aktif'] }}</div>
        <div class="senja-stat-label">Proker Aktif</div>
        <div class="senja-stat-sub">direncanakan & berjalan</div>
    </a>

    {{-- Pending --}}
    <a href="{{ \App\Filament\Resources\PendaftaranAnggotaResource::getUrl('index') }}"
       class="senja-card {{ $stats['pending_verifikasi'] > 0 ? 'senja-card-danger' : '' }}">
        <div style="display:flex;justify-content:space-between;align-items:flex-start">
            <div class="senja-icon" style="background:{{ $stats['pending_verifikasi'] > 0 ? '#fee2e2' : '#f1f5f9' }}">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="{{ $stats['pending_verifikasi'] > 0 ? '#dc2626' : '#64748b' }}" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            @if($stats['pending_verifikasi'] > 0)
                <span class="senja-badge" style="background:#fee2e2;color:#dc2626">Perlu Aksi</span>
            @endif
        </div>
        <div class="senja-stat-num" style="{{ $stats['pending_verifikasi'] > 0 ? 'color:#dc2626' : '' }}">{{ $stats['pending_verifikasi'] }}</div>
        <div class="senja-stat-label">Menunggu Verifikasi</div>
        <div class="senja-stat-sub">pendaftaran anggota baru</div>
    </a>

    {{-- Komposisi --}}
    <div class="senja-card" style="cursor:default">
        <div style="font-size:0.8125rem;font-weight:600;color:#374151;margin-bottom:14px">Komposisi Anggota</div>
        @php
            $komposisi = [
                ['label'=>'Aktif',      'count'=>$stats['anggota_aktif'],      'color'=>'#22c55e'],
                ['label'=>'Pasif',       'count'=>$stats['anggota_pasif'],      'color'=>'#f59e0b'],
                ['label'=>'Kehormatan',  'count'=>$stats['anggota_kehormatan'], 'color'=>'#3b82f6'],
            ];
            $total = max($stats['total_anggota'], 1);
        @endphp
        @foreach($komposisi as $k)
        <div style="margin-bottom:10px">
            <div style="display:flex;justify-content:space-between;font-size:0.75rem;color:#64748b;margin-bottom:4px">
                <span>{{ $k['label'] }}</span><span style="font-weight:600">{{ $k['count'] }}</span>
            </div>
            <div class="senja-progress-bar">
                <div style="height:100%;width:{{ ($k['count']/$total)*100 }}%;background:{{ $k['color'] }};border-radius:99px;transition:width 0.7s ease"></div>
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- Main Grid --}}
<div class="senja-main-grid">

    {{-- Pendaftaran Terbaru --}}
    <div class="senja-panel">
        <div class="senja-panel-header">
            <div class="senja-panel-title">
                <div class="senja-panel-icon" style="background:#fee2e2">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#dc2626" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                Pendaftaran Terbaru
            </div>
            <a href="{{ \App\Filament\Resources\PendaftaranAnggotaResource::getUrl('index') }}" class="senja-panel-link">
                Lihat semua →
            </a>
        </div>
        @if($pendaftaranTerbaru->isEmpty())
            <div class="senja-empty">
                <div style="width:44px;height:44px;border-radius:50%;background:#f1f5f9;display:flex;align-items:center;justify-content:center;margin:0 auto 10px">
                    <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#94a3b8" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                Belum ada pendaftaran masuk
            </div>
        @else
            @foreach($pendaftaranTerbaru as $daftar)
            <div class="senja-row">
                <div class="senja-avatar">{{ strtoupper(substr($daftar->nama_lengkap,0,1)) }}</div>
                <div style="flex:1;min-width:0">
                    <div style="font-size:0.875rem;font-weight:500;color:#0f172a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $daftar->nama_lengkap }}</div>
                    <div style="font-size:0.75rem;color:#64748b">{{ $daftar->nim }} &bull; {{ $daftar->divisi->nama ?? '—' }}</div>
                </div>
                <div style="text-align:right;flex-shrink:0">
                    <span class="senja-badge" style="background:#fef9c3;color:#854d0e">Menunggu</span>
                    <div style="font-size:0.7rem;color:#94a3b8;margin-top:3px">{{ $daftar->created_at->diffForHumans() }}</div>
                </div>
            </div>
            @endforeach
        @endif
    </div>

    {{-- Kegiatan Mendatang --}}
    <div class="senja-panel">
        <div class="senja-panel-header">
            <div class="senja-panel-title">
                <div class="senja-panel-icon" style="background:#dbeafe">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#2563eb" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                Kegiatan Mendatang
            </div>
            <a href="{{ \App\Filament\Resources\KegiatanResource::getUrl('index') }}" class="senja-panel-link">Semua →</a>
        </div>
        @if($kegiatanMendatang->isEmpty())
            <div class="senja-empty">Tidak ada kegiatan mendatang</div>
        @else
            @foreach($kegiatanMendatang as $kegiatan)
            <div class="senja-row" style="align-items:flex-start">
                <div class="senja-cal-box">
                    <div style="font-size:0.875rem;font-weight:700;color:#EA580C;line-height:1">{{ $kegiatan->tanggal_mulai->format('d') }}</div>
                    <div style="font-size:0.6875rem;color:#64748b">{{ $kegiatan->tanggal_mulai->format('M') }}</div>
                </div>
                <div style="flex:1;min-width:0">
                    <div style="font-size:0.875rem;font-weight:500;color:#0f172a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $kegiatan->nama }}</div>
                    <div style="font-size:0.75rem;color:#64748b">{{ $kegiatan->divisi->nama ?? '—' }} &bull; {{ $kegiatan->tanggal_mulai->format('H:i') }}</div>
                    <span class="senja-badge" style="background:{{ $kegiatan->status === 'Sedang Berlangsung' ? '#dcfce7' : '#dbeafe' }};color:{{ $kegiatan->status === 'Sedang Berlangsung' ? '#15803d' : '#1d4ed8' }};margin-top:4px;display:inline-block">
                        {{ $kegiatan->status }}
                    </span>
                </div>
            </div>
            @endforeach
        @endif
    </div>
</div>

{{-- Bottom Grid --}}
<div class="senja-bottom-grid">

    {{-- Proker --}}
    <div class="senja-panel">
        <div class="senja-panel-header">
            <div class="senja-panel-title">
                <div class="senja-panel-icon" style="background:#f3e8ff">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#9333ea" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                Program Kerja Aktif
            </div>
            <a href="{{ \App\Filament\Resources\ProgramKerjaResource::getUrl('index') }}" class="senja-panel-link">Semua →</a>
        </div>
        @if($prokerTerbaru->isEmpty())
            <div class="senja-empty">Belum ada program kerja aktif</div>
        @else
            @foreach($prokerTerbaru as $proker)
            <div class="senja-row">
                <div style="width:8px;height:8px;border-radius:50%;flex-shrink:0;background:{{ $proker->status === 'Sedang Berjalan' ? '#22c55e' : '#60a5fa' }}"></div>
                <div style="flex:1;min-width:0">
                    <div style="font-size:0.875rem;font-weight:500;color:#0f172a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $proker->nama }}</div>
                    <div style="font-size:0.75rem;color:#64748b">{{ $proker->divisi->nama ?? '—' }} &bull; {{ $proker->tanggal_mulai_estimasi->format('d M Y') }}</div>
                </div>
                <span class="senja-badge" style="background:{{ $proker->status === 'Sedang Berjalan' ? '#dcfce7' : '#dbeafe' }};color:{{ $proker->status === 'Sedang Berjalan' ? '#15803d' : '#1d4ed8' }};flex-shrink:0">
                    {{ $proker->status }}
                </span>
            </div>
            @endforeach
        @endif
    </div>

    {{-- Arsip Surat --}}
    <div class="senja-panel">
        <div class="senja-panel-header">
            <div class="senja-panel-title">
                <div class="senja-panel-icon" style="background:#ffedd5">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#EA580C" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                Arsip Surat Terbaru
            </div>
        </div>
        @forelse($suratMasukTerbaru as $surat)
        <div class="senja-row">
            <span class="senja-badge" style="background:#dbeafe;color:#1d4ed8;flex-shrink:0">Masuk</span>
            <div style="flex:1;min-width:0">
                <div style="font-size:0.875rem;font-weight:500;color:#0f172a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $surat->perihal }}</div>
                <div style="font-size:0.75rem;color:#64748b">{{ $surat->nomor_surat }} &bull; {{ $surat->tanggal_diterima->format('d M Y') }}</div>
            </div>
        </div>
        @empty
        @endforelse
        @forelse($suratKeluarTerbaru as $surat)
        <div class="senja-row">
            <span class="senja-badge" style="background:#ffedd5;color:#c2410c;flex-shrink:0">Keluar</span>
            <div style="flex:1;min-width:0">
                <div style="font-size:0.875rem;font-weight:500;color:#0f172a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $surat->perihal }}</div>
                <div style="font-size:0.75rem;color:#64748b">{{ $surat->nomor_surat }} &bull; {{ $surat->tanggal_dikirim->format('d M Y') }}</div>
            </div>
        </div>
        @empty
        @endforelse
        @if($suratMasukTerbaru->isEmpty() && $suratKeluarTerbaru->isEmpty())
        <div class="senja-empty">Belum ada arsip surat</div>
        @endif
    </div>
</div>

{{-- Kalender Kegiatan & Proker --}}
<div class="senja-panel" style="margin-bottom:20px;overflow:visible" x-data="senjaKalender({{ json_encode($kalenderEvents) }})">

    {{-- Panel Header --}}
    <div class="senja-panel-header" style="padding:18px 24px">
        <div class="senja-panel-title" style="font-size:0.9375rem">
            <div class="senja-panel-icon" style="background:linear-gradient(135deg,#3b82f6,#8b5cf6);width:36px;height:36px;border-radius:10px">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            Kalender Kegiatan & Program Kerja
        </div>
        <div style="display:flex;align-items:center;gap:16px">
            <div style="display:flex;align-items:center;gap:8px">
                <span style="display:flex;align-items:center;gap:4px;font-size:0.75rem;color:#64748b;font-weight:500">
                    <span style="width:8px;height:8px;border-radius:2px;background:#3b82f6;display:inline-block"></span>Kegiatan
                </span>
                <span style="display:flex;align-items:center;gap:4px;font-size:0.75rem;color:#64748b;font-weight:500">
                    <span style="width:8px;height:8px;border-radius:2px;background:#8b5cf6;display:inline-block"></span>Proker
                </span>
            </div>
        </div>
    </div>

    {{-- Body: 2 kolom --}}
    <div style="display:grid;grid-template-columns:1fr 300px;gap:0;min-height:420px">

        {{-- KIRI: Kalender --}}
        <div style="padding:20px 24px;border-right:1px solid #f1f5f9">

            {{-- Navigasi bulan --}}
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px">
                <button @click="prevMonth()"
                    style="width:34px;height:34px;border-radius:10px;border:1px solid #e2e8f0;background:white;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all 0.2s;box-shadow:0 1px 3px rgba(0,0,0,0.06)"
                    onmouseover="this.style.background='#f8fafc';this.style.borderColor='#cbd5e1'"
                    onmouseout="this.style.background='white';this.style.borderColor='#e2e8f0'">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#475569" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <div style="text-align:center">
                    <div style="font-size:1rem;font-weight:700;color:#0f172a" x-text="monthLabel"></div>
                </div>
                <button @click="nextMonth()"
                    style="width:34px;height:34px;border-radius:10px;border:1px solid #e2e8f0;background:white;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all 0.2s;box-shadow:0 1px 3px rgba(0,0,0,0.06)"
                    onmouseover="this.style.background='#f8fafc';this.style.borderColor='#cbd5e1'"
                    onmouseout="this.style.background='white';this.style.borderColor='#e2e8f0'">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#475569" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>

            {{-- Header hari --}}
            <div style="display:grid;grid-template-columns:repeat(7,1fr);margin-bottom:6px">
                <template x-for="day in ['Min','Sen','Sel','Rab','Kam','Jum','Sab']">
                    <div style="text-align:center;font-size:0.6875rem;font-weight:600;color:#94a3b8;letter-spacing:0.05em;text-transform:uppercase;padding:4px 0" x-text="day"></div>
                </template>
            </div>

            {{-- Grid hari --}}
            <div style="display:grid;grid-template-columns:repeat(7,1fr);gap:2px">
                <template x-for="cell in calendarCells" :key="cell.key">
                    <div
                        @click="cell.events.length > 0 && openDayModal(cell)"
                        :style="`
                            border-radius:10px;
                            padding:6px 4px;
                            min-height:56px;
                            position:relative;
                            transition:all 0.15s;
                            cursor:${cell.events.length > 0 ? 'pointer' : 'default'};
                            background:${cell.isToday ? 'linear-gradient(135deg,#fff7ed,#fff0e6)' : cell.events.length > 0 && cell.isCurrentMonth ? '#f8faff' : 'transparent'};
                            border:${cell.isToday ? '1.5px solid #fb923c' : cell.events.length > 0 && cell.isCurrentMonth ? '1px solid #e0e7ff' : '1px solid transparent'};
                        `"
                        onmouseover="if(this._hasEvents) { this.style.background='#f0f4ff'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px rgba(59,130,246,0.1)'; }"
                        onmouseout="if(this._hasEvents) { this.style.transform=''; this.style.boxShadow=''; }"
                        :_hasEvents="cell.events.length > 0">

                        {{-- Nomor hari --}}
                        <div style="display:flex;justify-content:flex-end;margin-bottom:3px">
                            <span :style="`
                                font-size:0.75rem;
                                font-weight:${cell.isToday ? '700' : '500'};
                                color:${cell.isToday ? '#ea580c' : cell.isCurrentMonth ? '#374151' : '#d1d5db'};
                                ${cell.isToday ? 'background:#ea580c;color:white;width:22px;height:22px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.6875rem' : ''}
                            `" x-text="cell.day"></span>
                        </div>

                        {{-- Event dots / pills --}}
                        <div style="display:flex;flex-direction:column;gap:2px">
                            <template x-for="(ev, idx) in cell.events.slice(0,2)" :key="idx">
                                <div :style="`
                                    font-size:0.6rem;
                                    font-weight:600;
                                    padding:1px 4px;
                                    border-radius:3px;
                                    white-space:nowrap;
                                    overflow:hidden;
                                    text-overflow:ellipsis;
                                    background:${ev.type==='kegiatan' ? '#dbeafe' : '#ede9fe'};
                                    color:${ev.type==='kegiatan' ? '#1e40af' : '#6d28d9'};
                                    border-left:2px solid ${ev.type==='kegiatan' ? '#3b82f6' : '#8b5cf6'};
                                `" x-text="ev.title"></div>
                            </template>
                            <template x-if="cell.events.length > 2">
                                <div style="font-size:0.6rem;color:#94a3b8;text-align:center;font-weight:500" x-text="`+${cell.events.length - 2}`"></div>
                            </template>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        {{-- KANAN: Upcoming list --}}
        <div style="display:flex;flex-direction:column;background:#fafafa">
            <div style="padding:16px 18px 10px;border-bottom:1px solid #f1f5f9">
                <p style="font-size:0.8125rem;font-weight:600;color:#374151">Akan Datang</p>
                <p style="font-size:0.75rem;color:#94a3b8;margin-top:1px" x-text="`${upcomingEvents.length} jadwal`"></p>
            </div>
            <div style="overflow-y:auto;flex:1;max-height:380px">
                <template x-if="upcomingEvents.length === 0">
                    <div style="padding:40px 18px;text-align:center">
                        <div style="width:44px;height:44px;border-radius:50%;background:#f1f5f9;display:flex;align-items:center;justify-content:center;margin:0 auto 10px">
                            <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#94a3b8" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <p style="font-size:0.8125rem;color:#94a3b8">Tidak ada jadwal</p>
                    </div>
                </template>
                <template x-for="(ev, i) in upcomingEvents.slice(0,8)" :key="i">
                    <div style="padding:10px 18px;border-bottom:1px solid #f1f5f9;cursor:pointer;transition:background 0.15s"
                         onmouseover="this.style.background='#f0f4ff'"
                         onmouseout="this.style.background='transparent'"
                         @click="openEventModal(ev)">
                        <div style="display:flex;align-items:flex-start;gap:10px">
                            {{-- Date box --}}
                            <div :style="`background:${ev.type==='kegiatan' ? '#eff6ff' : '#f5f3ff'};border-radius:8px;padding:6px 8px;text-align:center;min-width:40px;flex-shrink:0;border-top:3px solid ${ev.type==='kegiatan' ? '#3b82f6' : '#8b5cf6'}`">
                                <div :style="`font-size:1rem;font-weight:700;line-height:1;color:${ev.type==='kegiatan' ? '#1d4ed8' : '#6d28d9'}`" x-text="new Date(ev.start+'T00:00:00').getDate()"></div>
                                <div style="font-size:0.6rem;color:#94a3b8;text-transform:uppercase;font-weight:500" x-text="new Date(ev.start+'T00:00:00').toLocaleDateString('id-ID',{month:'short'})"></div>
                            </div>
                            <div style="flex:1;min-width:0">
                                <p style="font-size:0.8125rem;font-weight:600;color:#1e293b;overflow:hidden;text-overflow:ellipsis;white-space:nowrap" x-text="ev.title"></p>
                                <p style="font-size:0.75rem;color:#64748b;margin-top:2px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap" x-text="ev.divisi"></p>
                                <div style="margin-top:4px">
                                    <span :style="`font-size:0.6rem;font-weight:600;padding:2px 6px;border-radius:99px;background:${ev.type==='kegiatan' ? '#dbeafe' : '#ede9fe'};color:${ev.type==='kegiatan' ? '#1e40af' : '#6d28d9'}`"
                                          x-text="ev.type==='kegiatan' ? 'Kegiatan' : 'Proker'"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    {{-- Modal detail hari --}}
    <template x-if="dayModalOpen">
        <div style="position:fixed;inset:0;z-index:9999;display:flex;align-items:center;justify-content:center;padding:16px"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100">
            <div style="position:absolute;inset:0;background:rgba(15,23,42,0.6);backdrop-filter:blur(6px)" @click="dayModalOpen=false"></div>
            <div style="position:relative;background:white;border-radius:24px;width:100%;max-width:460px;max-height:82vh;overflow:hidden;display:flex;flex-direction:column;z-index:1;box-shadow:0 24px 80px rgba(0,0,0,0.25)">
                {{-- Gradient header --}}
                <div style="background:linear-gradient(135deg,#1e40af 0%,#7c3aed 100%);padding:24px 24px 20px;flex-shrink:0;position:relative;overflow:hidden">
                    <div style="position:absolute;right:-20px;top:-20px;width:120px;height:120px;border-radius:50%;background:rgba(255,255,255,0.06)"></div>
                    <div style="position:absolute;left:40%;bottom:-30px;width:80px;height:80px;border-radius:50%;background:rgba(255,255,255,0.04)"></div>
                    <div style="position:relative;z-index:1;display:flex;align-items:flex-start;justify-content:space-between">
                        <div>
                            <p style="color:rgba(255,255,255,0.6);font-size:0.6875rem;font-weight:600;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:4px">Jadwal Hari</p>
                            <p style="color:white;font-size:1.1875rem;font-weight:700;line-height:1.3" x-text="selectedDay?.label"></p>
                            <p style="color:rgba(255,255,255,0.7);font-size:0.8125rem;margin-top:4px" x-text="`${selectedDay?.events.length} agenda`"></p>
                        </div>
                        <button @click="dayModalOpen=false"
                            style="width:34px;height:34px;border-radius:50%;background:rgba(255,255,255,0.15);border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;flex-shrink:0;transition:background 0.15s"
                            onmouseover="this.style.background='rgba(255,255,255,0.25)'"
                            onmouseout="this.style.background='rgba(255,255,255,0.15)'">
                            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Event cards --}}
                <div style="overflow-y:auto;padding:20px;display:flex;flex-direction:column;gap:12px">
                    <template x-for="(ev, i) in selectedDay?.events" :key="i">
                        <div :style="`border-radius:16px;overflow:hidden;border:1px solid ${ev.type==='kegiatan' ? '#dbeafe' : '#ede9fe'};transition:box-shadow 0.15s`"
                             onmouseover="this.style.boxShadow='0 4px 20px rgba(0,0,0,0.08)'"
                             onmouseout="this.style.boxShadow='none'">
                            {{-- Card top accent --}}
                            <div :style="`height:4px;background:linear-gradient(90deg,${ev.type==='kegiatan' ? '#3b82f6,#60a5fa' : '#8b5cf6,#a78bfa'})`"></div>
                            <div style="padding:16px">
                                <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:8px;margin-bottom:12px">
                                    <div style="display:flex;align-items:center;gap:8px;flex:1;min-width:0">
                                        <div :style="`width:32px;height:32px;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;background:${ev.type==='kegiatan' ? '#eff6ff' : '#f5f3ff'}`">
                                            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" :stroke="ev.type==='kegiatan' ? '#3b82f6' : '#8b5cf6'" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p style="font-size:0.9375rem;font-weight:700;color:#0f172a;line-height:1.3" x-text="ev.title"></p>
                                        </div>
                                    </div>
                                    <span :style="`background:${ev.type==='kegiatan' ? '#dbeafe' : '#ede9fe'};color:${ev.type==='kegiatan' ? '#1e40af' : '#6d28d9'};font-size:0.6875rem;padding:3px 10px;border-radius:99px;font-weight:600;flex-shrink:0`"
                                          x-text="ev.type==='kegiatan' ? 'Kegiatan' : 'Proker'"></span>
                                </div>

                                <div style="display:grid;gap:8px;background:#f8fafc;border-radius:10px;padding:12px">
                                    <div style="display:flex;align-items:center;gap:8px">
                                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="#8b5cf6" stroke-width="2" style="flex-shrink:0"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                        <span style="font-size:0.8125rem;color:#475569;font-weight:500" x-text="ev.divisi"></span>
                                    </div>
                                    <template x-if="ev.lokasi">
                                        <div style="display:flex;align-items:center;gap:8px">
                                            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="#64748b" stroke-width="2" style="flex-shrink:0"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                            <span style="font-size:0.8125rem;color:#475569" x-text="ev.lokasi"></span>
                                        </div>
                                    </template>
                                    <div style="display:flex;align-items:center;gap:8px">
                                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="#64748b" stroke-width="2" style="flex-shrink:0"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <span style="font-size:0.8125rem;color:#475569" x-text="ev.start === ev.end ? ev.start : ev.start + ' — ' + ev.end"></span>
                                    </div>
                                    <div style="display:flex;align-items:center;gap:8px">
                                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="#64748b" stroke-width="2" style="flex-shrink:0"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3"/></svg>
                                        <span :style="`font-size:0.8125rem;font-weight:600;color:${ev.status==='Sedang Berlangsung'||ev.status==='Sedang Berjalan' ? '#15803d' : ev.status==='Selesai'||ev.status==='Dibatalkan' ? '#64748b' : '#1d4ed8'}`"
                                              x-text="ev.status"></span>
                                    </div>
                                </div>

                                <template x-if="ev.deskripsi">
                                    <p style="font-size:0.8125rem;color:#64748b;line-height:1.6;margin-top:10px;padding-top:10px;border-top:1px solid #f1f5f9" x-text="ev.deskripsi"></p>
                                </template>

                                <a :href="ev.edit_url"
                                   style="display:inline-flex;align-items:center;gap:6px;margin-top:12px;font-size:0.8125rem;font-weight:600;color:#EA580C;text-decoration:none;padding:6px 12px;border-radius:8px;background:#fff7ed;border:1px solid #fed7aa;transition:all 0.15s"
                                   onmouseover="this.style.background='#ffedd5';this.style.borderColor='#fb923c'"
                                   onmouseout="this.style.background='#fff7ed';this.style.borderColor='#fed7aa'">
                                    <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    Edit di Admin
                                </a>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </template>

    {{-- Modal event single (dari klik upcoming) --}}
    <template x-if="eventModalOpen">
        <div style="position:fixed;inset:0;z-index:9999;display:flex;align-items:center;justify-content:center;padding:16px">
            <div style="position:absolute;inset:0;background:rgba(15,23,42,0.6);backdrop-filter:blur(6px)" @click="eventModalOpen=false"></div>
            <div style="position:relative;background:white;border-radius:24px;width:100%;max-width:420px;overflow:hidden;z-index:1;box-shadow:0 24px 80px rgba(0,0,0,0.25)">
                <div :style="`height:5px;background:linear-gradient(90deg,${selectedEvent?.type==='kegiatan' ? '#3b82f6,#60a5fa' : '#8b5cf6,#a78bfa'})`"></div>
                <div style="padding:24px">
                    <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:16px">
                        <div style="flex:1;min-width:0;padding-right:12px">
                            <span :style="`font-size:0.6875rem;font-weight:600;padding:3px 8px;border-radius:99px;background:${selectedEvent?.type==='kegiatan' ? '#dbeafe' : '#ede9fe'};color:${selectedEvent?.type==='kegiatan' ? '#1e40af' : '#6d28d9'}`"
                                  x-text="selectedEvent?.type==='kegiatan' ? 'Kegiatan' : 'Proker'"></span>
                            <p style="font-size:1rem;font-weight:700;color:#0f172a;margin-top:8px;line-height:1.3" x-text="selectedEvent?.title"></p>
                        </div>
                        <button @click="eventModalOpen=false"
                            style="width:32px;height:32px;border-radius:50%;background:#f1f5f9;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;flex-shrink:0;transition:background 0.15s"
                            onmouseover="this.style.background='#e2e8f0'"
                            onmouseout="this.style.background='#f1f5f9'">
                            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="#475569" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    <div style="display:grid;gap:8px;background:#f8fafc;border-radius:12px;padding:14px;margin-bottom:14px">
                        <div style="display:flex;align-items:center;gap:8px">
                            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#8b5cf6" stroke-width="2" style="flex-shrink:0"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            <span style="font-size:0.875rem;color:#374151;font-weight:500" x-text="selectedEvent?.divisi"></span>
                        </div>
                        <template x-if="selectedEvent?.lokasi">
                            <div style="display:flex;align-items:center;gap:8px">
                                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#64748b" stroke-width="2" style="flex-shrink:0"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span style="font-size:0.875rem;color:#374151" x-text="selectedEvent?.lokasi"></span>
                            </div>
                        </template>
                        <div style="display:flex;align-items:center;gap:8px">
                            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#64748b" stroke-width="2" style="flex-shrink:0"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span style="font-size:0.875rem;color:#374151" x-text="selectedEvent?.start === selectedEvent?.end ? selectedEvent?.start : (selectedEvent?.start + ' — ' + selectedEvent?.end)"></span>
                        </div>
                        <div style="display:flex;align-items:center;gap:8px">
                            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#64748b" stroke-width="2" style="flex-shrink:0"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3"/></svg>
                            <span :style="`font-size:0.875rem;font-weight:600;color:${selectedEvent?.status==='Sedang Berlangsung'||selectedEvent?.status==='Sedang Berjalan' ? '#15803d' : '#1d4ed8'}`"
                                  x-text="selectedEvent?.status"></span>
                        </div>
                    </div>
                    <template x-if="selectedEvent?.deskripsi">
                        <p style="font-size:0.875rem;color:#64748b;line-height:1.6;margin-bottom:14px" x-text="selectedEvent?.deskripsi"></p>
                    </template>
                    <a :href="selectedEvent?.edit_url"
                       style="display:flex;align-items:center;justify-content:center;gap:6px;padding:10px;border-radius:10px;background:linear-gradient(135deg,#EA580C,#c94a09);color:white;text-decoration:none;font-size:0.875rem;font-weight:600;transition:opacity 0.15s"
                       onmouseover="this.style.opacity='0.9'"
                       onmouseout="this.style.opacity='1'">
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Edit di Admin
                    </a>
                </div>
            </div>
        </div>
    </template>
</div>

{{-- Piket Hari Ini --}}
@if(count($jadwalPiketHariIni) > 0)
<div class="senja-panel" style="margin-bottom:20px">
    <div class="senja-panel-header">
        <div class="senja-panel-title">
            <div class="senja-panel-icon" style="background:#fff0e6">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#EA580C" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            Piket Hari Ini — {{ now()->translatedFormat('l, d F Y') }}
        </div>
        <a href="{{ \App\Filament\Resources\LaporanPiketResource::getUrl('index') }}"
           class="senja-panel-link">Lihat Laporan →</a>
    </div>

    @foreach($jadwalPiketHariIni as $jp)
    <div style="padding:16px 20px;{{ ! $loop->last ? 'border-bottom:1px solid #f1f5f9;' : '' }}">

        {{-- Header jadwal --}}
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;flex-wrap:wrap;gap:8px">
            <div style="display:flex;align-items:center;gap:10px">
                <div style="background:#fff0e6;border-radius:8px;padding:6px 12px;font-size:0.8125rem;font-weight:700;color:#EA580C">
                    Piket {{ $jp['nama_hari'] }}
                </div>
                <span style="font-size:0.75rem;color:#64748b">
                    Mulai {{ $jp['jam_mulai'] }} &nbsp;•&nbsp; Batas upload {{ $jp['batas_upload'] }}
                </span>
                @if($jp['deadline_lewat'])
                <span style="font-size:0.65rem;padding:2px 7px;border-radius:99px;background:#fee2e2;color:#dc2626;font-weight:600">Deadline Lewat</span>
                @else
                <span style="font-size:0.65rem;padding:2px 7px;border-radius:99px;background:#dcfce7;color:#15803d;font-weight:600">Masih Aktif</span>
                @endif
            </div>
            {{-- Progress bar ringkasan --}}
            <div style="display:flex;align-items:center;gap:10px">
                <div style="text-align:right">
                    <div style="font-size:0.75rem;font-weight:600;color:#0f172a">{{ $jp['sudah_upload'] }}/{{ $jp['total'] }} upload</div>
                    <div style="font-size:0.7rem;color:#94a3b8">
                        <span style="color:#16a34a">{{ $jp['tepat_waktu'] }} tepat</span>
                        @if($jp['terlambat'] > 0) &nbsp;•&nbsp; <span style="color:#d97706">{{ $jp['terlambat'] }} terlambat</span>@endif
                        @if($jp['belum_upload'] > 0) &nbsp;•&nbsp; <span style="color:#94a3b8">{{ $jp['belum_upload'] }} belum</span>@endif
                    </div>
                </div>
                {{-- Mini donut progress --}}
                @php
                    $pct = $jp['total'] > 0 ? round(($jp['sudah_upload'] / $jp['total']) * 100) : 0;
                @endphp
                <div style="width:44px;height:44px;border-radius:50%;background:conic-gradient(#22c55e {{ $pct }}%, #f1f5f9 0);display:flex;align-items:center;justify-content:center;position:relative">
                    <div style="width:32px;height:32px;border-radius:50%;background:#fff;display:flex;align-items:center;justify-content:center;font-size:0.6rem;font-weight:700;color:#0f172a">{{ $pct }}%</div>
                </div>
            </div>
        </div>

        {{-- Tabel anggota --}}
        @if(count($jp['rows']) > 0)
        <div style="border:1px solid #f1f5f9;border-radius:10px;overflow:hidden">
            <div style="display:grid;grid-template-columns:1fr auto auto;background:#f8fafc;padding:8px 14px;font-size:0.7rem;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:0.04em;gap:12px">
                <span>Anggota</span>
                <span style="text-align:center">Jam Upload</span>
                <span style="text-align:center;min-width:90px">Status</span>
            </div>
            @foreach($jp['rows'] as $row)
            @php
                $sc = match($row['status']) {
                    'Tepat Waktu'  => ['bg'=>'#dcfce7','color'=>'#15803d'],
                    'Terlambat'    => ['bg'=>'#fef9c3','color'=>'#854d0e'],
                    'Tidak Hadir'  => ['bg'=>'#fee2e2','color'=>'#dc2626'],
                    default        => ['bg'=>'#f1f5f9','color'=>'#475569'],
                };
            @endphp
            <div style="display:grid;grid-template-columns:1fr auto auto;padding:10px 14px;border-top:1px solid #f1f5f9;align-items:center;gap:12px;transition:background 0.15s"
                 onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                <div>
                    <div style="font-size:0.8125rem;font-weight:500;color:#0f172a">{{ $row['nama'] }}</div>
                    <div style="font-size:0.7rem;color:#94a3b8">{{ $row['nim'] }}</div>
                </div>
                <div style="text-align:center;font-size:0.75rem;color:#64748b;font-variant-numeric:tabular-nums">
                    {{ $row['jam_upload'] ?? '—' }}
                </div>
                <div style="text-align:center;min-width:90px">
                    <span style="font-size:0.65rem;padding:2px 9px;border-radius:99px;font-weight:600;background:{{ $sc['bg'] }};color:{{ $sc['color'] }}">
                        {{ $row['status'] }}
                    </span>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div style="padding:24px;text-align:center;color:#94a3b8;font-size:0.875rem">
            Belum ada anggota yang ditugaskan pada jadwal ini.
        </div>
        @endif
    </div>
    @endforeach
</div>
@else
<div class="senja-panel" style="margin-bottom:20px">
    <div class="senja-panel-header">
        <div class="senja-panel-title">
            <div class="senja-panel-icon" style="background:#fff0e6">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#EA580C" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            Piket Hari Ini — {{ now()->translatedFormat('l, d F Y') }}
        </div>
        <a href="{{ \App\Filament\Resources\JadwalPiketResource::getUrl('index') }}"
           class="senja-panel-link">Atur Jadwal →</a>
    </div>
    <div style="padding:40px 20px;text-align:center;color:#94a3b8;font-size:0.875rem">
        <div style="width:44px;height:44px;border-radius:50%;background:#f1f5f9;display:flex;align-items:center;justify-content:center;margin:0 auto 10px">
            <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#94a3b8" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
        </div>
        Tidak ada jadwal piket aktif untuk hari ini.
    </div>
</div>
@endif

{{-- Quick Actions --}}
<div class="senja-panel" style="margin-bottom:0">
    <div class="senja-panel-header">
        <div class="senja-panel-title">
            <div class="senja-panel-icon" style="background:#f0f2f8">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#64748b" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            Aksi Cepat
        </div>
    </div>
    <div style="padding:16px">
        <div class="senja-quick-grid">
            <a href="{{ \App\Filament\Resources\AnggotaResource::getUrl('create') }}"
               class="senja-quick-btn" style="background:#f0fdf4;color:#15803d">
                <div class="senja-quick-icon">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#15803d" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                </div>
                Tambah Anggota
            </a>
            <a href="{{ \App\Filament\Resources\SuratMasukResource::getUrl('create') }}"
               class="senja-quick-btn" style="background:#eff6ff;color:#1d4ed8">
                <div class="senja-quick-icon">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#1d4ed8" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                </div>
                Catat Surat Masuk
            </a>
            <a href="{{ \App\Filament\Resources\SuratKeluarResource::getUrl('create') }}"
               class="senja-quick-btn" style="background:#fff7ed;color:#c2410c">
                <div class="senja-quick-icon">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#c2410c" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                </div>
                Buat Surat Keluar
            </a>
            <a href="{{ \App\Filament\Resources\KegiatanResource::getUrl('create') }}"
               class="senja-quick-btn" style="background:#faf5ff;color:#7e22ce">
                <div class="senja-quick-icon">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#7e22ce" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                Tambah Kegiatan
            </a>
        </div>
    </div>
    {{-- Script Alpine kalender (di sini supaya tetap dalam satu root element) --}}
    <script>
function senjaKalender(events) {
    return {
        events,
        currentYear: new Date().getFullYear(),
        currentMonth: new Date().getMonth(),
        dayModalOpen: false,
        eventModalOpen: false,
        selectedDay: null,
        selectedEvent: null,

        get monthLabel() {
            return new Date(this.currentYear, this.currentMonth, 1)
                .toLocaleDateString('id-ID', { month: 'long', year: 'numeric' });
        },

        prevMonth() {
            if (this.currentMonth === 0) { this.currentMonth = 11; this.currentYear--; }
            else this.currentMonth--;
        },
        nextMonth() {
            if (this.currentMonth === 11) { this.currentMonth = 0; this.currentYear++; }
            else this.currentMonth++;
        },

        eventsForDate(dateStr) {
            return this.events.filter(ev => ev.start <= dateStr && dateStr <= ev.end);
        },

        get upcomingEvents() {
            const today = new Date();
            const todayStr = `${today.getFullYear()}-${String(today.getMonth()+1).padStart(2,'0')}-${String(today.getDate()).padStart(2,'0')}`;
            return this.events
                .filter(ev => ev.end >= todayStr)
                .sort((a, b) => a.start.localeCompare(b.start));
        },

        get calendarCells() {
            const year = this.currentYear, month = this.currentMonth;
            const firstDay = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();
            const daysInPrev = new Date(year, month, 0).getDate();
            const today = new Date();
            const todayStr = `${today.getFullYear()}-${String(today.getMonth()+1).padStart(2,'0')}-${String(today.getDate()).padStart(2,'0')}`;
            const cells = [];

            for (let i = firstDay - 1; i >= 0; i--) {
                const d = daysInPrev - i;
                const m = month === 0 ? 12 : month;
                const y = month === 0 ? year - 1 : year;
                const dateStr = `${y}-${String(m).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
                cells.push({ key: `prev-${d}`, day: d, isCurrentMonth: false, isToday: false, events: this.eventsForDate(dateStr) });
            }
            for (let d = 1; d <= daysInMonth; d++) {
                const dateStr = `${year}-${String(month+1).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
                const label = new Date(year, month, d).toLocaleDateString('id-ID', { weekday:'long', day:'numeric', month:'long', year:'numeric' });
                cells.push({ key: `cur-${d}`, day: d, isCurrentMonth: true, isToday: dateStr === todayStr, events: this.eventsForDate(dateStr), date: dateStr, label });
            }
            const remaining = 42 - cells.length;
            for (let d = 1; d <= remaining; d++) {
                const m = month === 11 ? 1 : month + 2;
                const y = month === 11 ? year + 1 : year;
                const dateStr = `${y}-${String(m).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
                cells.push({ key: `next-${d}`, day: d, isCurrentMonth: false, isToday: false, events: this.eventsForDate(dateStr) });
            }
            return cells;
        },

        openDayModal(cell) {
            this.selectedDay = cell;
            this.dayModalOpen = true;
        },

        openEventModal(ev) {
            this.selectedEvent = ev;
            this.eventModalOpen = true;
        },

        init() {
            document.addEventListener('keydown', e => {
                if (e.key === 'Escape') { this.dayModalOpen = false; this.eventModalOpen = false; }
            });
        }
    }
}
</script>
</div>

@endif {{-- end @if($isAnggota) / @else --}}

</x-filament-panels::page>
