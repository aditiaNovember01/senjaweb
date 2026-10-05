<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Divisi;

class KeanggotaanController extends Controller
{
    public function index()
    {
        $semuaAnggota = Anggota::with('divisi', 'angkatan', 'pengurus')
            ->orderBy('nama_lengkap')
            ->get();

        $anggotaPerDivisi = Divisi::withCount([
                'anggotas as anggota_aktif_count' => fn ($q) => $q->where('status', 'Anggota Aktif'),
            ])
            ->having('anggota_aktif_count', '>', 0)
            ->get();

        $stats = [
            'pembina'            => Anggota::where('status', 'Pembina')->count(),
            'anggota_aktif'      => Anggota::where('status', 'Anggota Aktif')->count(),
            'anggota_pasif'      => Anggota::where('status', 'Anggota Pasif')->count(),
            'anggota_kehormatan' => Anggota::where('status', 'Anggota Kehormatan')->count(),
        ];

        return view('keanggotaan.index', compact(
            'semuaAnggota',
            'anggotaPerDivisi',
            'stats'
        ));
    }
}
