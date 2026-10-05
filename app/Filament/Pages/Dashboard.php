<?php

namespace App\Filament\Pages;

use App\Models\Anggota;
use App\Models\DendaPiket;
use App\Models\JadwalPiket;
use App\Models\Kegiatan;
use App\Models\LaporanPiket;
use App\Models\PendaftaranAnggota;
use App\Models\PeriodeKepengurusan;
use App\Models\ProgramKerja;
use App\Models\SiteSetting;
use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use Filament\Pages\Page;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $view = 'filament.pages.dashboard';

    protected static ?string $navigationLabel = 'Dashboard';

    protected static ?string $title = 'Dashboard';

    protected static ?int $navigationSort = -1;

    public function getViewData(): array
    {
        $user      = auth()->user();
        $isAnggota = $user?->isAnggota();
        $anggotaId = $user?->anggota_id;

        if ($isAnggota) {
            return $this->getAnggotaViewData($anggotaId);
        }

        return $this->getAdminViewData();
    }

    // ── Dashboard Anggota (terbatas) ───────────────────────────────────────────

    private function getAnggotaViewData(?int $anggotaId): array
    {
        $anggota = $anggotaId
            ? Anggota::with('divisi', 'angkatan')->find($anggotaId)
            : null;

        // Jadwal piket yang ditugaskan (berbasis hari), eager load laporan hari ini
        $today       = now()->toDateString();
        $jadwalPiket = $anggotaId
            ? JadwalPiket::whereHas('anggotas', fn ($q) => $q->where('anggotas.id', $anggotaId))
                ->where('is_aktif', true)
                ->orderBy('hari_ke')
                ->with([
                    'laporanPikets' => fn ($q) => $q->where('anggota_id', $anggotaId),
                ])
                ->get()
            : collect();

        $sudahUpload = $anggotaId
            ? LaporanPiket::where('anggota_id', $anggotaId)->count()
            : 0;

        $tepatWaktu = $anggotaId
            ? LaporanPiket::where('anggota_id', $anggotaId)
                ->where('status_kehadiran', 'Tepat Waktu')->count()
            : 0;

        $terlambat = $anggotaId
            ? LaporanPiket::where('anggota_id', $anggotaId)
                ->where('status_kehadiran', 'Terlambat')->count()
            : 0;

        $kegiatanMendatang = Kegiatan::where('tanggal_selesai', '>=', now())
            ->whereIn('status', ['Akan Datang', 'Sedang Berlangsung'])
            ->orderBy('tanggal_mulai')
            ->with('divisi')
            ->limit(5)
            ->get();

        return [
            'isAnggota'         => true,
            'anggota'           => $anggota,
            'jadwalPiket'       => $jadwalPiket,
            'today'             => $today,
            'sudahUpload'       => $sudahUpload,
            'tepatWaktu'        => $tepatWaktu,
            'terlambat'         => $terlambat,
            'kegiatanMendatang' => $kegiatanMendatang,
            'infoRadius' => [
                'aktif'          => (float) SiteSetting::get('piket_lat', '0') != 0.0,
                'radius'         => (int)   SiteSetting::get('piket_radius_meter', '50'),
                'sekretariat'    => SiteSetting::get('contact_address', 'Sekretariat UKM SENJA'),
            ],
            'dendaAnggota' => $anggotaId ? DendaPiket::where('anggota_id', $anggotaId)
                ->orderByDesc('tanggal_piket')
                ->limit(5)
                ->get() : collect(),
            'totalDendaBelumBayar' => $anggotaId
                ? DendaPiket::where('anggota_id', $anggotaId)->where('sudah_dibayar', false)->sum('nominal')
                : 0,
            'stats'              => [],
            'pendaftaranTerbaru' => collect(),
            'prokerTerbaru'      => collect(),
            'periodeAktif'       => null,
            'suratMasukTerbaru'  => collect(),
            'suratKeluarTerbaru' => collect(),
            'kalenderEvents'     => collect(),
        ];
    }

    // ── Dashboard Admin / Ketua / Sekretaris / Pengurus ────────────────────────

    private function getAdminViewData(): array
    {
        Kegiatan::syncAllStatuses();

        $periodeAktif = PeriodeKepengurusan::where('is_aktif', true)->first();

        $kegiatanKalender = Kegiatan::where(function ($q) {
                $q->whereBetween('tanggal_mulai', [now()->startOfMonth(), now()->addMonths(3)->endOfMonth()])
                  ->orWhere(function ($q2) {
                      $q2->where('tanggal_mulai', '<', now()->startOfMonth())
                         ->where('tanggal_selesai', '>=', now()->startOfMonth());
                  });
            })
            ->with('divisi')
            ->get()
            ->map(fn ($k) => [
                'id'        => $k->id,
                'type'      => 'kegiatan',
                'title'     => $k->nama,
                'start'     => $k->tanggal_mulai->format('Y-m-d'),
                'end'       => $k->tanggal_selesai ? $k->tanggal_selesai->format('Y-m-d') : $k->tanggal_mulai->format('Y-m-d'),
                'status'    => $k->status,
                'divisi'    => $k->divisi->nama ?? '—',
                'lokasi'    => $k->lokasi ?? '',
                'deskripsi' => $k->deskripsi ?? '',
                'edit_url'  => \App\Filament\Resources\KegiatanResource::getUrl('edit', ['record' => $k->id]),
            ]);

        $prokerKalender = ProgramKerja::whereBetween('tanggal_mulai_estimasi', [now()->startOfMonth(), now()->addMonths(3)->endOfMonth()])
            ->when($periodeAktif, fn ($q) => $q->where('periode_id', $periodeAktif->id))
            ->with('divisi')
            ->get()
            ->map(fn ($p) => [
                'id'        => $p->id,
                'type'      => 'proker',
                'title'     => $p->nama,
                'start'     => $p->tanggal_mulai_estimasi->format('Y-m-d'),
                'end'       => $p->tanggal_selesai_estimasi ? $p->tanggal_selesai_estimasi->format('Y-m-d') : $p->tanggal_mulai_estimasi->format('Y-m-d'),
                'status'    => $p->status,
                'divisi'    => $p->divisi->nama ?? '—',
                'lokasi'    => '',
                'deskripsi' => $p->deskripsi ?? '',
                'edit_url'  => \App\Filament\Resources\ProgramKerjaResource::getUrl('edit', ['record' => $p->id]),
            ]);

        $kalenderEvents = $kegiatanKalender->concat($prokerKalender)->values();

        return [
            'isAnggota' => false,
            'stats' => [
                'anggota_aktif'      => Anggota::where('status', 'Anggota Aktif')->count(),
                'total_anggota'      => Anggota::count(),
                'surat_masuk'        => SuratMasuk::count(),
                'surat_keluar'       => SuratKeluar::count(),
                'proker_aktif'       => ProgramKerja::whereIn('status', ['Direncanakan', 'Sedang Berjalan'])->count(),
                'pending_verifikasi' => PendaftaranAnggota::count(),
                'anggota_pasif'      => Anggota::where('status', 'Anggota Pasif')->count(),
                'anggota_kehormatan' => Anggota::where('status', 'Anggota Kehormatan')->count(),
            ],
            'pendaftaranTerbaru' => PendaftaranAnggota::with('divisi')->latest()->limit(5)->get(),
            'kegiatanMendatang'  => Kegiatan::where(function ($q) {
                    $q->where('tanggal_selesai', '>=', now())
                      ->orWhere('tanggal_mulai', '>=', now());
                })
                ->whereIn('status', ['Akan Datang', 'Sedang Berlangsung'])
                ->orderBy('tanggal_mulai')
                ->with('divisi')
                ->limit(5)
                ->get(),
            'prokerTerbaru'      => ProgramKerja::whereIn('status', ['Direncanakan', 'Sedang Berjalan'])
                ->when($periodeAktif, fn ($q) => $q->where('periode_id', $periodeAktif->id))
                ->with('divisi')
                ->limit(4)
                ->get(),
            'periodeAktif'       => $periodeAktif,
            'suratMasukTerbaru'  => SuratMasuk::with('kategori')->latest()->limit(3)->get(),
            'suratKeluarTerbaru' => SuratKeluar::with('kategori')->latest()->limit(3)->get(),
            'kalenderEvents'     => $kalenderEvents,
            'jadwalPiketHariIni' => $this->getPiketHariIni(),
            'dendaStats'         => $this->getDendaStats(),
        ];
    }

    // ── Piket hari ini untuk dashboard admin ───────────────────────────────────

    private function getPiketHariIni(): array
    {
        $today   = now()->toDateString();
        $hariIni = now()->dayOfWeek;

        $jadwals = JadwalPiket::where('hari_ke', $hariIni)
            ->where('is_aktif', true)
            ->with([
                'anggotas',
                'laporanPikets' => fn ($q) => $q->where('tanggal_laporan', $today)
                    ->with('anggota'),
            ])
            ->get();

        return $jadwals->map(function ($jadwal) use ($today) {
            $totalPeserta  = $jadwal->anggotas->count();
            $sudahUpload   = $jadwal->laporanPikets->count();
            $tepatWaktu    = $jadwal->laporanPikets->where('status_kehadiran', 'Tepat Waktu')->count();
            $terlambat     = $jadwal->laporanPikets->where('status_kehadiran', 'Terlambat')->count();
            $belumUpload   = $totalPeserta - $sudahUpload;

            $rows = $jadwal->anggotas->map(function ($anggota) use ($jadwal, $today) {
                $laporan    = $jadwal->laporanPikets->firstWhere('anggota_id', $anggota->id);
                if ($laporan) {
                    $status  = $laporan->status_kehadiran;
                    $jamUpload = $laporan->uploaded_at?->format('H:i');
                } elseif ($jadwal->isDeadlineLewat($today)) {
                    $status  = 'Tidak Hadir';
                    $jamUpload = null;
                } else {
                    $status  = 'Belum Upload';
                    $jamUpload = null;
                }
                return [
                    'nama'      => $anggota->nama_lengkap,
                    'nim'       => $anggota->nim,
                    'status'    => $status,
                    'jam_upload' => $jamUpload,
                ];
            })->sortBy('nama')->values()->toArray();

            return [
                'id'           => $jadwal->id,
                'nama_hari'    => $jadwal->nama_hari,
                'jam_mulai'    => $jadwal->jam_mulai,
                'batas_upload' => $jadwal->batas_upload,
                'total'        => $totalPeserta,
                'sudah_upload' => $sudahUpload,
                'tepat_waktu'  => $tepatWaktu,
                'terlambat'    => $terlambat,
                'belum_upload' => $belumUpload,
                'rows'         => $rows,
                'deadline_lewat' => $jadwal->isDeadlineLewat($today),
            ];
        })->values()->toArray();
    }

    // ── Ringkasan denda untuk dashboard ───────────────────────────────────────

    private function getDendaStats(): array
    {
        return [
            'total_belum_bayar'  => DendaPiket::where('sudah_dibayar', false)->count(),
            'nominal_belum_bayar'=> DendaPiket::where('sudah_dibayar', false)->sum('nominal'),
            'total_lunas'        => DendaPiket::where('sudah_dibayar', true)->count(),
            'nominal_lunas'      => DendaPiket::where('sudah_dibayar', true)->sum('nominal'),
            'denda_terbaru'      => DendaPiket::with('anggota', 'jadwalPiket')
                ->where('sudah_dibayar', false)
                ->orderByDesc('tanggal_piket')
                ->limit(5)
                ->get(),
        ];
    }
}
