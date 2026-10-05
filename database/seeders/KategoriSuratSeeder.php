<?php

namespace Database\Seeders;

use App\Models\KategoriSurat;
use Illuminate\Database\Seeder;

class KategoriSuratSeeder extends Seeder
{
    public function run(): void
    {
        $kategoris = [
            'Undangan',
            'SK (Surat Keputusan)',
            'Permohonan',
            'Pemberitahuan',
            'Umum',
        ];

        foreach ($kategoris as $nama) {
            KategoriSurat::firstOrCreate(['nama' => $nama]);
        }
    }
}
