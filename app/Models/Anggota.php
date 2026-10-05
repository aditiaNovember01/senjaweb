<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Anggota extends Model
{
    public static array $statusOptions = [
        'Pembina'            => 'Pembina',
        'Anggota Aktif'      => 'Anggota Aktif',
        'Anggota Pasif'      => 'Anggota Pasif',
        'Anggota Kehormatan' => 'Anggota Kehormatan',
    ];

    protected $fillable = [
        'nama_lengkap',
        'nim',
        'email',
        'nomor_telepon',
        'divisi_id',
        'angkatan_id',
        'tanggal_bergabung',
        'status',
        'foto_profil',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_bergabung' => 'date',
        ];
    }

    public function divisi(): BelongsTo
    {
        return $this->belongsTo(Divisi::class);
    }

    public function angkatan(): BelongsTo
    {
        return $this->belongsTo(Angkatan::class);
    }

    public function pengurus(): HasMany
    {
        return $this->hasMany(Pengurus::class);
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    /** Jadwal piket yang ditugaskan ke anggota ini */
    public function jadwalPikets(): BelongsToMany
    {
        return $this->belongsToMany(JadwalPiket::class, 'jadwal_piket_anggota')
            ->withTimestamps();
    }

    /** Laporan piket yang sudah dikirim anggota ini */
    public function laporanPikets(): HasMany
    {
        return $this->hasMany(LaporanPiket::class);
    }

    /** Denda piket anggota ini */
    public function dendaPikets(): HasMany
    {
        return $this->hasMany(DendaPiket::class);
    }
}
