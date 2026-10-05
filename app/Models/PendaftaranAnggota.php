<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PendaftaranAnggota extends Model
{
    protected $fillable = [
        'nama_lengkap',
        'nim',
        'email',
        'nomor_telepon',
        'divisi_id',
        'prestasi',
        'berkas',
        'status',
    ];

    public function divisi(): BelongsTo
    {
        return $this->belongsTo(Divisi::class);
    }
}
