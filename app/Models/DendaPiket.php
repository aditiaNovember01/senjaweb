<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DendaPiket extends Model
{
    public const NOMINAL_DEFAULT = 5000; // Rp5.000 per tidak hadir

    protected $fillable = [
        'jadwal_piket_id',
        'anggota_id',
        'tanggal_piket',
        'alasan',
        'nominal',
        'sudah_dibayar',
        'dibayar_at',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_piket' => 'date',
            'sudah_dibayar' => 'boolean',
            'dibayar_at'    => 'datetime',
        ];
    }

    // ── Relationships ──────────────────────────────────────────────────────────

    public function jadwalPiket(): BelongsTo
    {
        return $this->belongsTo(JadwalPiket::class);
    }

    public function anggota(): BelongsTo
    {
        return $this->belongsTo(Anggota::class);
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    public function getNominalFormatAttribute(): string
    {
        return 'Rp ' . number_format($this->nominal, 0, ',', '.');
    }

    /**
     * Generate denda "Tidak Hadir" untuk semua jadwal aktif hari ini
     * yang sudah lewat deadline tapi anggota belum upload.
     * Dipanggil dari artisan command / scheduler.
     */
    public static function generateHariIni(): int
    {
        $today   = now()->toDateString();
        $hariIni = now()->dayOfWeek;
        $jumlah  = 0;

        $jadwals = JadwalPiket::where('hari_ke', $hariIni)
            ->where('is_aktif', true)
            ->with('anggotas')
            ->get();

        foreach ($jadwals as $jadwal) {
            // Lewat deadline baru hitung
            if (! $jadwal->isDeadlineLewat($today)) {
                continue;
            }

            foreach ($jadwal->anggotas as $anggota) {
                $sudahUpload = LaporanPiket::where('jadwal_piket_id', $jadwal->id)
                    ->where('anggota_id', $anggota->id)
                    ->where('tanggal_laporan', $today)
                    ->exists();

                if (! $sudahUpload) {
                    // updateOrCreate supaya tidak duplikat
                    $created = self::updateOrCreate(
                        [
                            'anggota_id'    => $anggota->id,
                            'tanggal_piket' => $today,
                        ],
                        [
                            'jadwal_piket_id' => $jadwal->id,
                            'alasan'          => 'Tidak Hadir',
                            'nominal'         => self::NOMINAL_DEFAULT,
                            'sudah_dibayar'   => false,
                        ]
                    );
                    if ($created->wasRecentlyCreated) {
                        $jumlah++;
                    }
                }
            }
        }

        return $jumlah;
    }
}
