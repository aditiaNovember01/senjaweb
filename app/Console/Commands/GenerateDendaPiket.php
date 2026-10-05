<?php

namespace App\Console\Commands;

use App\Models\DendaPiket;
use Illuminate\Console\Command;

class GenerateDendaPiket extends Command
{
    protected $signature   = 'piket:generate-denda';
    protected $description = 'Generate denda Rp5.000 untuk anggota yang tidak hadir piket hari ini (jalankan setelah batas upload lewat).';

    public function handle(): int
    {
        $jumlah = DendaPiket::generateHariIni();
        $this->info("Denda berhasil dibuat: {$jumlah} record baru.");
        return self::SUCCESS;
    }
}
