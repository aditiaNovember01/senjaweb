<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaporanPiket extends Model
{
    protected $fillable = [
        'jadwal_piket_id',
        'anggota_id',
        'tanggal_laporan',
        'foto_bukti',
        'catatan',
        'uploaded_at',
        'status_kehadiran',
        'menit_keterlambatan',
        'latitude',
        'longitude',
        'jarak_meter',
        'lokasi_valid',
    ];

    protected function casts(): array
    {
        return [
            // Pakai 'datetime' agar Laravel simpan/baca dengan timezone app
            'uploaded_at'     => 'datetime',
            // tanggal_laporan cukup date — tidak ada komponen jam
            'tanggal_laporan' => 'date:Y-m-d',
            'latitude'        => 'decimal:7',
            'longitude'       => 'decimal:7',
            'lokasi_valid'    => 'boolean',
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

    // ── Boot: auto-set timestamp & status saat create ──────────────────────────

    protected static function booted(): void
    {
        static::creating(function (self $model) {
            $tz = config('app.timezone', 'Asia/Jakarta');

            // uploaded_at — set sekarang (WIB) jika belum ada
            if (! $model->uploaded_at) {
                $model->uploaded_at = now($tz)->toDateTimeString();
            }

            // tanggal_laporan — ambil dari uploaded_at dalam WIB
            if (! $model->tanggal_laporan) {
                $model->tanggal_laporan = Carbon::parse(
                    $model->uploaded_at,
                    $tz
                )->toDateString();
            }

            // Hitung status keterlambatan
            if ($model->jadwal_piket_id) {
                $jadwal = JadwalPiket::find($model->jadwal_piket_id);
                if ($jadwal) {
                    // Parse uploaded_at dalam timezone app
                    $uploadedAt = Carbon::parse($model->uploaded_at, $tz);

                    // Tanggal laporan sebagai Y-m-d string
                    $tanggalStr = $model->tanggal_laporan instanceof \Carbon\Carbon
                        ? $model->tanggal_laporan->toDateString()
                        : substr((string) $model->tanggal_laporan, 0, 10);

                    $hasil = $jadwal->hitungStatus($uploadedAt, $tanggalStr);

                    $model->status_kehadiran    = $hasil['status'];
                    $model->menit_keterlambatan = $hasil['menit'];
                }
            }
        });
    }

    // ── Haversine ─────────────────────────────────────────────────────────────

    public static function hitungJarak(
        float $lat1, float $lng1,
        float $lat2, float $lng2
    ): int {
        $r    = 6371000;
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        $a    = sin($dLat / 2) ** 2
              + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng / 2) ** 2;
        return (int) round($r * 2 * atan2(sqrt($a), sqrt(1 - $a)));
    }

    // ── Accessors ─────────────────────────────────────────────────────────────

    public function getLabelKeterlambatanAttribute(): string
    {
        if ($this->status_kehadiran === 'Tepat Waktu') {
            return 'Tepat Waktu';
        }
        $menit = $this->menit_keterlambatan ?? 0;
        if ($menit < 60) {
            return "Terlambat {$menit} menit";
        }
        $jam  = intdiv($menit, 60);
        $sisa = $menit % 60;
        return $sisa > 0 ? "Terlambat {$jam} jam {$sisa} menit" : "Terlambat {$jam} jam";
    }

    public function getLabelLokasiAttribute(): string
    {
        if ($this->jarak_meter === null) return 'Tidak ada data GPS';
        $valid = $this->lokasi_valid ? '✓ Valid' : '✗ Di luar radius';
        return "{$this->jarak_meter} m — {$valid}";
    }
}
