<?php

namespace App\Http\Controllers;

use App\Models\Divisi;
use App\Models\PeriodeKepengurusan;
use App\Models\Pengurus;
use App\Models\SiteSetting;

class ProfilController extends Controller
{
    public function index()
    {
        $divisis = Divisi::withCount('anggotas')->get();

        $periodeAktif = PeriodeKepengurusan::where('is_aktif', true)->first();

        $pengurus = $periodeAktif
            ? Pengurus::where('periode_id', $periodeAktif->id)
                ->with('anggota.divisi')
                ->orderBy('urutan')
                ->get()
            : collect();

        $settings = SiteSetting::all_settings();

        return view('profil', compact('divisis', 'periodeAktif', 'pengurus', 'settings'));
    }
}
