<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Ambil laporan piket terbaru
$rec = \App\Models\LaporanPiket::latest()->first();
if (!$rec) { echo "Tidak ada record laporan piket.\n"; exit; }

echo "foto_bukti field : " . $rec->foto_bukti . "\n";
$path = storage_path('app/public/' . $rec->foto_bukti);
echo "Absolute path    : " . $path . "\n";
echo "File exists      : " . (file_exists($path) ? "YA" : "TIDAK") . "\n";

if (file_exists($path)) {
    echo "MIME             : " . mime_content_type($path) . "\n";
    echo "Filesize         : " . filesize($path) . " bytes\n";

    // Coba apply watermark langsung
    $svc = new \App\Services\WatermarkService();
    $result = $svc->apply(
        storagePath: $rec->foto_bukti,
        latitude:    $rec->latitude ? (float)$rec->latitude : null,
        longitude:   $rec->longitude ? (float)$rec->longitude : null,
        uploadedAt:  $rec->uploaded_at
            ? \Carbon\Carbon::parse($rec->uploaded_at)
                ->timezone(config('app.timezone', 'Asia/Jakarta'))
                ->toDateTimeString()
            : null,
    );
    echo "Watermark result : " . ($result ? "SUKSES" : "GAGAL") . "\n";
} else {
    echo "\nCek isi folder storage:\n";
    $dir = storage_path('app/public/bukti-piket');
    if (is_dir($dir)) {
        foreach (glob($dir . '/*') as $f) {
            echo "  " . basename($f) . "\n";
        }
    } else {
        echo "  Folder bukti-piket tidak ada\n";
    }
}
