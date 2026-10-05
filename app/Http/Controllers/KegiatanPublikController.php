<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;

class KegiatanPublikController extends Controller
{
    public function index()
    {
        $kegiatanMendatang = Kegiatan::whereIn('status', ['Akan Datang', 'Sedang Berlangsung'])
            ->orderBy('tanggal_mulai')
            ->with('divisi')
            ->paginate(9);

        $kegiatanSelesai = Kegiatan::where('status', 'Selesai')
            ->orderByDesc('tanggal_mulai')
            ->with('divisi')
            ->limit(6)
            ->get();

        return view('kegiatan.index', compact('kegiatanMendatang', 'kegiatanSelesai'));
    }

    public function show(Kegiatan $kegiatan)
    {
        $kegiatan->load('divisi', 'galeriFotos');

        return view('kegiatan.show', compact('kegiatan'));
    }
}
