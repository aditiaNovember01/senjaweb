<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JadwalPiket extends Model
{
    protected $fillable = [
        'hari_ke',
        'jam_mulai',
        'batas_upload',
        'keterangan',
        'is_aktif',
    ];

    protected function casts(): array
    {
        return [
            'is_aktif' => 'boolean',
        ];
    }

    // ── Constants ──────────────────────────────────────────────────────────────

    public static array $hariOptions = [
        0 => 'Minggu',
        1 => 'Senin',
        2 => 'Selasa',
        3 => 'Rabu',
        4 => 'Kamis',
        5 => "Jum'at",
        6 => 'Sabtu',
    ];

    // ── Accessors ──────────────────────────────────────────────────────────────

    public function getNamaHariAttribute(): string
    {
        return self::$hariOptions[$this->hari_ke] ?? "Hari {$this->hari_ke}";
    }

    // ── Relationships ──────────────────────────────────────────────────────────

    public function anggotas(): BelongsToMany
    {
        return $this->belongsToMany(Anggota::class, 'jadwal_piket_anggota')
            ->withTimestamps();
    }

    public function laporanPikets(): HasMany
    {
        return $this->hasMany(LaporanPiket::class);
    }

    public function dendaPikets(): HasMany
    {
        return $this->hasMany(DendaPiket::class);
    }

    // ── Helpers ────────────────────────────────────────────────────────────────

    /**
     * Tanggal aktual HARI INI yang cocok dengan hari_ke jadwal.
     * Dipakai untuk menentukan konteks laporan hari ini.
     */
    public function tanggalHariIni(): ?Carbon
    {
        $today = now();
        if ($today->dayOfWeek === $this->hari_ke) {
            return $today->startOfDay();
        }
        return null; // bukan jadwal hari ini
    }

    /**
     * Apakah jadwal ini aktif HARI INI?
     */
    public function isHariIni(): bool
    {
        return $this->is_aktif && now()->dayOfWeek === $this->hari_ke;
    }

    /**
     * Hitung status keterlambatan berdasarkan jam upload vs batas_upload.
     * Tanggal yang dipakai adalah tanggal laporan aktual.
     */
    /**
     * Hitung status keterlambatan berdasarkan waktu upload vs batas_upload.
     * Semua perbandingan pakai datetime penuh (YYYY-MM-DD HH:MM:SS) dengan timezone app.
     *
     * @param  Carbon  $uploadedAt  — waktu upload aktual (sudah dalam app timezone)
     * @param  string|Carbon  $tanggalLaporan  — tanggal laporan (Y-m-d)
     */
    public function hitungStatus(Carbon $uploadedAt, string|Carbon $tanggalLaporan): array
    {
        // Ambil hanya Y-m-d
        $tanggalStr = $tanggalLaporan instanceof Carbon
            ? $tanggalLaporan->toDateString()
            : substr((string) $tanggalLaporan, 0, 10);

        // Normalkan batas_upload ke H:i:s
        $jamStr = $this->normalizeTime($this->batas_upload);

        // Buat datetime penuh batas upload dalam timezone app
        $batas = Carbon::createFromFormat(
            'Y-m-d H:i:s',
            "{$tanggalStr} {$jamStr}",
            config('app.timezone')
        );

        // Pastikan uploadedAt juga dalam timezone app
        $uploadedAtLocal = $uploadedAt->copy()->setTimezone(config('app.timezone'));

        if ($uploadedAtLocal->lte($batas)) {
            return ['status' => 'Tepat Waktu', 'menit' => 0];
        }

        $menit = (int) $batas->diffInMinutes($uploadedAtLocal);
        return ['status' => 'Terlambat', 'menit' => $menit];
    }

    /**
     * Apakah deadline sudah lewat untuk tanggal tertentu?
     */
    public function isDeadlineLewat(string|Carbon $tanggal): bool
    {
        $tanggalStr = $tanggal instanceof Carbon
            ? $tanggal->toDateString()
            : substr((string) $tanggal, 0, 10);

        $jamStr = $this->normalizeTime($this->batas_upload);

        $batas = Carbon::createFromFormat(
            'Y-m-d H:i:s',
            "{$tanggalStr} {$jamStr}",
            config('app.timezone')
        );

        return now()->isAfter($batas);
    }

    /**
     * Normalkan format waktu dari DB (H:i atau H:i:s) menjadi H:i:s.
     */
    private function normalizeTime(string $time): string
    {
        // Jika sudah H:i:s (8+ karakter), kembalikan apa adanya
        // Jika H:i (5 karakter), tambah :00
        $parts = explode(':', $time);
        return match (count($parts)) {
            2 => "{$parts[0]}:{$parts[1]}:00",
            default => $time,
        };
    }

    /**
     * Status anggota untuk tanggal laporan tertentu.
     */
    public function statusAnggota(int $anggotaId, string $tanggal): string
    {
        $laporan = $this->laporanPikets()
            ->where('anggota_id', $anggotaId)
            ->where('tanggal_laporan', $tanggal)
            ->first();

        if ($laporan) {
            return $laporan->status_kehadiran;
        }

        if ($this->isDeadlineLewat($tanggal)) {
            return 'Tidak Hadir';
        }

        return 'Belum Upload';
    }
}
