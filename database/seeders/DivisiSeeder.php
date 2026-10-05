<?php

namespace Database\Seeders;

use App\Models\Divisi;
use Illuminate\Database\Seeder;

class DivisiSeeder extends Seeder
{
    public function run(): void
    {
        $divisis = [
            ['nama' => 'Tari',    'deskripsi' => 'Divisi Seni Tari SENJA'],
            ['nama' => 'Musik',   'deskripsi' => 'Divisi Seni Musik SENJA'],
            ['nama' => 'Teater',  'deskripsi' => 'Divisi Seni Teater SENJA'],
            ['nama' => 'Rupa',    'deskripsi' => 'Divisi Seni Rupa SENJA'],
            ['nama' => 'Sastra',  'deskripsi' => 'Divisi Sastra SENJA'],
        ];

        foreach ($divisis as $divisi) {
            Divisi::firstOrCreate(['nama' => $divisi['nama']], $divisi);
        }
    }
}
