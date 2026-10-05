<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kegiatan extends Model
{
    protected $fillable = [
        'nama',
        'deskripsi',
        'tanggal_mulai',
        'tanggal_selesai',
        'lokasi',
        'divisi_id',
        'status',
        'gambar_poster',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mulai'   => 'datetime',
            'tanggal_selesai' => 'datetime',
        ];
    }

    /**
     * Hitung status berdasarkan tanggal saat ini.
     * Tidak mengubah status 'Selesai' atau 'Dibatalkan' yang sudah di-set manual.
     */
    public function computedStatus(): string
    {
        // Status final tidak diubah otomatis
        if (in_array($this->status, ['Selesai', 'Dibatalkan'])) {
            return $this->status;
        }

        $now = now();

        if ($now < $this->tanggal_mulai) {
            return 'Akan Datang';
        }

        if ($now >= $this->tanggal_mulai && $now <= $this->tanggal_selesai) {
            return 'Sedang Berlangsung';
        }

        // tanggal_selesai sudah lewat, otomatis Selesai
        return 'Selesai';
    }

    /**
     * Sinkronisasi status ke database sesuai tanggal.
     */
    public function syncStatus(): void
    {
        $computed = $this->computedStatus();
        if ($computed !== $this->status) {
            $this->update(['status' => $computed]);
        }
    }

    /**
     * Sinkronisasi semua kegiatan yang statusnya perlu diupdate.
     */
    public static function syncAllStatuses(): void
    {
        $now = now();

        // Akan Datang → Sedang Berlangsung
        static::where('status', 'Akan Datang')
            ->where('tanggal_mulai', '<=', $now)
            ->where('tanggal_selesai', '>=', $now)
            ->update(['status' => 'Sedang Berlangsung']);

        // Sedang Berlangsung → Selesai
        static::where('status', 'Sedang Berlangsung')
            ->where('tanggal_selesai', '<', $now)
            ->update(['status' => 'Selesai']);

        // Akan Datang yang tanggal_selesai sudah lewat → Selesai
        static::where('status', 'Akan Datang')
            ->where('tanggal_selesai', '<', $now)
            ->update(['status' => 'Selesai']);
    }

    public function divisi(): BelongsTo
    {
        return $this->belongsTo(Divisi::class);
    }

    public function galeriFotos(): HasMany
    {
        return $this->hasMany(GaleriFoto::class);
    }
}
