<?php

namespace App\Http\Controllers;

use App\Models\PeriodeKepengurusan;
use App\Models\ProgramKerja;

class ProgramKerjaPublikController extends Controller
{
    public function index()
    {
        $periodeAktif = PeriodeKepengurusan::where('is_aktif', true)->first();

        $prokers = ProgramKerja::whereIn('status', ['Direncanakan', 'Sedang Berjalan'])
            ->when($periodeAktif, fn ($q) => $q->where('periode_id', $periodeAktif->id))
            ->orderBy('tanggal_mulai_estimasi')
            ->with('divisi', 'periode')
            ->paginate(9);

        return view('program-kerja.index', compact('prokers', 'periodeAktif'));
    }

    public function show(ProgramKerja $programKerja)
    {
        $programKerja->load('divisi', 'periode');

        return view('program-kerja.show', compact('programKerja'));
    }
}
