<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuratMasuk extends Model
{
    protected $fillable = [
        'nomor_surat',
        'tanggal_surat',
        'tanggal_diterima',
        'nama_pengirim',
        'perihal',
        'kategori_id',
        'berkas_pdf',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_surat'    => 'date',
            'tanggal_diterima' => 'date',
        ];
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriSurat::class, 'kategori_id');
    }
}
