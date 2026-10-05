<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuratKeluar extends Model
{
    protected $fillable = [
        'nomor_surat',
        'tanggal_surat',
        'tanggal_dikirim',
        'nama_penerima',
        'perihal',
        'kategori_id',
        'berkas_pdf',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_surat'   => 'date',
            'tanggal_dikirim' => 'date',
        ];
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriSurat::class, 'kategori_id');
    }
}
