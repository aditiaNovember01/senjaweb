<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgramKerja extends Model
{
    protected $fillable = [
        'nama',
        'deskripsi',
        'divisi_id',
        'ketua_pelaksana_id',
        'periode_id',
        'tanggal_mulai_estimasi',
        'tanggal_selesai_estimasi',
        'status',
        'target_peserta',
        'estimasi_anggaran',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mulai_estimasi'   => 'date',
            'tanggal_selesai_estimasi' => 'date',
            'estimasi_anggaran'        => 'decimal:2',
        ];
    }

    public function divisi(): BelongsTo
    {
        return $this->belongsTo(Divisi::class);
    }

    public function ketuaPelaksana(): BelongsTo
    {
        return $this->belongsTo(Anggota::class, 'ketua_pelaksana_id');
    }

    public function pengurus(): BelongsTo
    {
        return $this->belongsTo(Pengurus::class);
    }

    public function periode(): BelongsTo
    {
        return $this->belongsTo(PeriodeKepengurusan::class, 'periode_id');
    }
}
