<?php

namespace App\Http\Controllers;

use App\Models\GaleriWebsite;

class GaleriController extends Controller
{
    public function index()
    {
        $galeri = GaleriWebsite::where('is_aktif', true)
            ->orderBy('urutan')
            ->orderBy('created_at')
            ->get();

        return view('galeri', compact('galeri'));
    }
}
