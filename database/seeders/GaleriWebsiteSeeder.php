<?php

namespace Database\Seeders;

use App\Models\GaleriWebsite;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class GaleriWebsiteSeeder extends Seeder
{
    public function run(): void
    {
        $folder = public_path('assets/galleryfoto');

        if (!File::exists($folder)) {
            $this->command->warn('Folder assets/galleryfoto tidak ditemukan.');
            return;
        }

        $files = File::files($folder);
        $urutan = 1;

        foreach ($files as $file) {
            $filename = $file->getFilename();
            $path     = 'assets/galleryfoto/' . $filename;

            GaleriWebsite::firstOrCreate(
                ['path_foto' => $path],
                [
                    'judul'      => 'Foto Kegiatan ' . $urutan,
                    'sumber'     => 'assets',
                    'keterangan' => '',
                    'is_aktif'   => true,
                    'urutan'     => $urutan,
                ]
            );

            $urutan++;
        }

        $this->command->info("Berhasil seed {$urutan} foto galeri.");
    }
}
