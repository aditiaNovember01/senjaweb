<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Divisi;
use App\Models\GaleriWebsite;
use App\Models\Kegiatan;
use App\Models\PeriodeKepengurusan;
use App\Models\ProgramKerja;
use App\Models\SiteSetting;

class BerandaController extends Controller
{
    public function index()
    {
        $periodeAktif = PeriodeKepengurusan::where('is_aktif', true)->first();

        $kegiatanMendatang = Kegiatan::whereIn('status', ['Akan Datang', 'Sedang Berlangsung'])
            ->orderBy('tanggal_mulai')
            ->limit(5)
            ->with('divisi')
            ->get();

        $prokerAktif = $periodeAktif
            ? ProgramKerja::where('periode_id', $periodeAktif->id)
                ->whereIn('status', ['Direncanakan', 'Sedang Berjalan'])
                ->orderBy('tanggal_mulai_estimasi')
                ->limit(5)
                ->with('divisi')
                ->get()
            : collect();

        $divisis = Divisi::withCount('anggotas')->get();

        $anggotaSlider = Anggota::where('status', 'Anggota Aktif')
            ->with('divisi', 'angkatan', 'pengurus')
            ->inRandomOrder()
            ->limit(20)
            ->get();

        $stats = [
            'anggota_aktif' => Anggota::where('status', 'Anggota Aktif')->count(),
            'divisi'        => $divisis->count(),
            'kegiatan'      => Kegiatan::whereIn('status', ['Selesai', 'Sedang Berlangsung', 'Akan Datang'])->count(),
        ];

        $settings = SiteSetting::all_settings();

        // Foto pengurus untuk beranda — ambil yang is_beranda=true, paling terbaru
        $fotoBeranda = GaleriWebsite::where('is_aktif', true)
            ->where('is_beranda', true)
            ->latest()
            ->first();

        return view('beranda', compact(
            'periodeAktif',
            'kegiatanMendatang',
            'prokerAktif',
            'divisis',
            'anggotaSlider',
            'stats',
            'settings',
            'fotoBeranda'
        ));
    }
}
