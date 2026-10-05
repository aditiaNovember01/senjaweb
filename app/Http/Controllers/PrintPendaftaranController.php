<?php

namespace App\Http\Controllers;

use App\Models\PendaftaranAnggota;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class PrintPendaftaranController extends Controller
{
    public function cetak(Request $request, int $id)
    {
        // Hanya user yang sudah login (admin panel) yang boleh akses
        abort_unless(auth()->check() && auth()->user()->isAdmin(), 403);

        $pendaftaran = PendaftaranAnggota::with('divisi')->findOrFail($id);

        $settings = [
            'org_name'         => SiteSetting::get('org_name', 'UKM SENJA'),
            'contact_address'  => SiteSetting::get('contact_address', ''),
            'contact_email'    => SiteSetting::get('contact_email', ''),
            'contact_phone'    => SiteSetting::get('contact_phone', ''),
            'social_instagram' => SiteSetting::get('social_instagram', ''),
        ];

        return view('print.bukti-pendaftaran', compact('pendaftaran', 'settings'));
    }
}
