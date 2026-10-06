<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class WatermarkService
{
    /**
     * Tambahkan watermark lokasi, tanggal, dan jam ke gambar.
     *
     * @param  string      $storagePath  Path relatif dari storage/app/public, contoh: "bukti-piket/foto.jpg"
     * @param  float|null  $latitude
     * @param  float|null  $longitude
     * @param  string|null $uploadedAt   DateTime string (Y-m-d H:i:s) sudah dalam timezone lokal
     * @return bool
     */
    public function apply(
        string $storagePath,
        ?float $latitude,
        ?float $longitude,
        ?string $uploadedAt
    ): bool {
        $absolutePath = storage_path('app/public/' . $storagePath);

        if (! file_exists($absolutePath)) {
            Log::warning("WatermarkService: file tidak ditemukan — {$absolutePath}");
            return false;
        }

        if (! extension_loaded('gd')) {
            Log::warning('WatermarkService: ekstensi GD tidak tersedia.');
            return false;
        }

        // ── Load gambar ──────────────────────────────────────────────────────
        $mime = mime_content_type($absolutePath);
        $src  = match ($mime) {
            'image/jpeg' => @imagecreatefromjpeg($absolutePath),
            'image/png'  => @imagecreatefrompng($absolutePath),
            'image/webp' => @imagecreatefromwebp($absolutePath),
            default      => false,
        };

        if (! $src) {
            Log::warning("WatermarkService: gagal load gambar ({$mime}) — {$absolutePath}");
            return false;
        }

        $w = imagesx($src);
        $h = imagesy($src);

        // ── Teks watermark ───────────────────────────────────────────────────
        $tz = config('app.timezone', 'Asia/Jakarta');

        if ($uploadedAt) {
            $dt     = \Carbon\Carbon::parse($uploadedAt, $tz);
            $tglStr = $dt->translatedFormat('d M Y');
            $jamStr = $dt->format('H:i:s') . ' WIB';
        } else {
            $now    = now($tz);
            $tglStr = $now->translatedFormat('d M Y');
            $jamStr = $now->format('H:i:s') . ' WIB';
        }

        if ($latitude !== null && $longitude !== null) {
            $lokasiStr = sprintf('%.6f, %.6f', $latitude, $longitude);
        } else {
            $lokasiStr = 'Lokasi tidak tersedia';
        }

        $lines = [
            '📍 ' . $lokasiStr,
            '📅 ' . $tglStr,
            '🕐 ' . $jamStr,
        ];

        // ── Warna & font ─────────────────────────────────────────────────────
        // Cari font TrueType — gunakan bawaan sistem atau fallback ke GD built-in
        $fontPath = $this->findFont();
        $fontSize = max(14, (int) ($w * 0.022)); // ~2.2% lebar gambar

        // Padding strip di bawah
        $lineHeight = $fontSize + 8;
        $stripH     = $lineHeight * count($lines) + 16;
        $stripY     = $h - $stripH;

        // Gambar strip hitam semi-transparan
        $overlay = imagecreatetruecolor($w, $stripH);
        $black   = imagecolorallocate($overlay, 0, 0, 0);
        imagefill($overlay, 0, 0, $black);
        imagecopymerge($src, $overlay, 0, $stripY, 0, 0, $w, $stripH, 55); // 55% opacity
        imagedestroy($overlay);

        // Teks putih
        $white  = imagecolorallocate($src, 255, 255, 255);
        $shadow = imagecolorallocate($src, 30, 30, 30);

        foreach ($lines as $i => $line) {
            $y = $stripY + 12 + ($i * $lineHeight) + $fontSize;

            if ($fontPath) {
                // TrueType — shadow dulu, lalu teks
                imagettftext($src, $fontSize, 0, 13, $y + 1, $shadow, $fontPath, $line);
                imagettftext($src, $fontSize, 0, 12, $y, $white, $fontPath, $line);
            } else {
                // Fallback: built-in font (font 5 = terbesar built-in)
                $bx = 12;
                $by = $stripY + 12 + ($i * $lineHeight);
                imagestring($src, 5, $bx + 1, $by + 1, $line, $shadow);
                imagestring($src, 5, $bx, $by, $line, $white);
            }
        }

        // ── Simpan kembali ────────────────────────────────────────────────────
        $ok = match ($mime) {
            'image/jpeg' => imagejpeg($src, $absolutePath, 92),
            'image/png'  => imagepng($src, $absolutePath, 6),
            'image/webp' => imagewebp($src, $absolutePath, 92),
            default      => false,
        };

        imagedestroy($src);
        return (bool) $ok;
    }

    // ── Cari font TrueType ────────────────────────────────────────────────────

    private function findFont(): ?string
    {
        $candidates = [
            // Fonts bawaan dari project (simpan di resources/fonts/ jika ada)
            base_path('resources/fonts/OpenSans-Regular.ttf'),
            base_path('resources/fonts/Roboto-Regular.ttf'),
            base_path('resources/fonts/DejaVuSans.ttf'),
            // Linux system fonts
            '/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf',
            '/usr/share/fonts/truetype/liberation/LiberationSans-Regular.ttf',
            '/usr/share/fonts/truetype/ubuntu/Ubuntu-R.ttf',
            // Windows system fonts (Laragon)
            'C:/Windows/Fonts/arial.ttf',
            'C:/Windows/Fonts/segoeui.ttf',
            'C:/Windows/Fonts/calibri.ttf',
        ];

        foreach ($candidates as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }

        return null;
    }
}
