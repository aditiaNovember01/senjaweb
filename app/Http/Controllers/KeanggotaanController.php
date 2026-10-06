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

        $anggotaPerDivisi = Divisi::withCount('anggotas')
            ->having('anggotas_count', '>', 0)
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

    public function divisi(Divisi $divisi)
    {
        // Semua anggota divisi ini — semua status, bukan hanya aktif
        $anggotas = Anggota::with('angkatan', 'pengurus')
            ->where('divisi_id', $divisi->id)
            ->orderByRaw("FIELD(status, 'Pembina','Anggota Aktif','Anggota Kehormatan','Anggota Pasif')")
            ->orderBy('nama_lengkap')
            ->get();

        $stats = [
            'total'              => $anggotas->count(),
            'aktif'              => $anggotas->where('status', 'Anggota Aktif')->count(),
            'pasif'              => $anggotas->where('status', 'Anggota Pasif')->count(),
            'kehormatan'         => $anggotas->where('status', 'Anggota Kehormatan')->count(),
            'pembina'            => $anggotas->where('status', 'Pembina')->count(),
        ];

        return view('keanggotaan.divisi', compact('divisi', 'anggotas', 'stats'));
    }
}
