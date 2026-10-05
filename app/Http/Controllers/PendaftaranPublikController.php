<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Divisi;
use App\Models\PendaftaranAnggota;
use Illuminate\Http\Request;

class PendaftaranPublikController extends Controller
{
    public function create()
    {
        $divisis = Divisi::orderBy('nama')->get();

        return view('pendaftaran.create', compact('divisis'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap'  => ['required', 'string', 'max:100'],
            'nim'           => [
                'required', 'string',
                'unique:anggotas,nim',
                'unique:pendaftaran_anggotas,nim',
            ],
            'email'         => [
                'required', 'email',
                'unique:anggotas,email',
                'unique:pendaftaran_anggotas,email',
            ],
            'nomor_telepon' => ['required', 'string', 'max:20'],
            'divisi_id'     => ['required', 'exists:divisis,id'],
            'prestasi'      => ['nullable', 'string', 'max:2000'],
            'berkas'        => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ], [
            'nim.unique'   => 'NIM tersebut sudah terdaftar atau sedang dalam proses verifikasi.',
            'email.unique' => 'Email tersebut sudah terdaftar atau sedang dalam proses verifikasi.',
        ]);

        // Handle file upload
        if ($request->hasFile('berkas')) {
            $validated['berkas'] = $request->file('berkas')->store('berkas-pendaftaran', 'public');
        }

        PendaftaranAnggota::create($validated);

        return redirect()->route('pendaftaran.sukses');
    }

    public function sukses()
    {
        return view('pendaftaran.sukses');
    }
}
