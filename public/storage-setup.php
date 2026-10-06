<?php
/**
 * Storage Setup Helper — hapus file ini setelah digunakan!
 * Akses via: https://senjaweb.gauld.my.id/storage-setup.php
 *
 * File ini membuat symlink public/storage → storage/app/public
 * Hanya bisa diakses dengan password.
 */

// ── Ganti password ini sebelum upload ─────────────────────────
define('SETUP_PASSWORD', 'senja2026setup');
// ──────────────────────────────────────────────────────────────

$pass = $_POST['password'] ?? $_GET['p'] ?? '';
$authenticated = ($pass === SETUP_PASSWORD);

// Paths
$root       = dirname(__DIR__);
$publicLink = __DIR__ . '/storage';
$target     = $root . '/storage/app/public';

$messages = [];
$success  = false;

if ($authenticated && isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'symlink') {
        // Buat folder storage/app/public kalau belum ada
        if (!is_dir($target)) {
            mkdir($target, 0755, true);
            $messages[] = "✅ Folder {$target} dibuat.";
        }

        // Hapus link/folder lama kalau ada
        if (is_link($publicLink)) {
            unlink($publicLink);
            $messages[] = "🗑️ Symlink lama dihapus.";
        } elseif (is_dir($publicLink)) {
            $messages[] = "⚠️ Ada folder biasa di public/storage — hapus manual dulu.";
        }

        // Buat symlink
        if (!file_exists($publicLink)) {
            if (symlink($target, $publicLink)) {
                $messages[] = "✅ Symlink berhasil: public/storage → storage/app/public";
                $success = true;
            } else {
                $messages[] = "❌ Gagal buat symlink. Coba via SSH: ln -s {$target} {$publicLink}";
            }
        }
    }

    if ($action === 'fix_permission') {
        $dirs = [
            $root . '/storage',
            $root . '/storage/app',
            $root . '/storage/app/public',
            $root . '/bootstrap/cache',
        ];
        foreach ($dirs as $dir) {
            if (is_dir($dir)) {
                chmod($dir, 0755);
                $messages[] = "✅ chmod 755: {$dir}";
            }
        }
        $success = true;
    }
}

$symlinkStatus = is_link($publicLink) ? '✅ Ada → ' . readlink($publicLink) : '❌ Tidak ada';
$folderStatus  = is_dir($target) ? '✅ Ada' : '❌ Tidak ada';
$testUrl       = 'https://' . ($_SERVER['HTTP_HOST'] ?? 'domain.com') . '/storage';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Storage Setup</title>
<style>
body{font-family:Arial,sans-serif;max-width:600px;margin:40px auto;padding:20px;background:#f5f5f5}
.card{background:#fff;border-radius:10px;padding:24px;box-shadow:0 2px 8px rgba(0,0,0,.1);margin-bottom:16px}
h2{color:#EA580C;margin:0 0 16px}
.status{display:flex;gap:10px;padding:8px 12px;background:#f8fafc;border-radius:6px;margin-bottom:8px;font-size:.9rem}
.msg{padding:10px 14px;border-radius:6px;margin-bottom:6px;font-size:.875rem}
.msg.ok{background:#d1fae5;color:#065f46}
.msg.err{background:#fee2e2;color:#991b1b}
.msg.warn{background:#fef3c7;color:#92400e}
input[type=password],input[type=text]{width:100%;padding:10px;border:1px solid #e5e7eb;border-radius:6px;margin-bottom:10px;box-sizing:border-box}
button{background:#EA580C;color:#fff;border:none;padding:10px 20px;border-radius:6px;cursor:pointer;font-weight:600;margin-right:8px}
button:hover{background:#c94a09}
.danger{background:#dc2626}
.danger:hover{background:#b91c1c}
code{background:#f1f5f9;padding:2px 6px;border-radius:4px;font-size:.85rem}
</style>
</head>
<body>

<div class="card">
    <h2>🔧 Storage Setup Helper</h2>
    <p style="color:#dc2626;font-size:.85rem"><strong>⚠️ HAPUS FILE INI SETELAH DIGUNAKAN!</strong></p>

    <div class="status"><span>📁 storage/app/public :</span> <strong><?= $folderStatus ?></strong></div>
    <div class="status"><span>🔗 public/storage symlink :</span> <strong><?= $symlinkStatus ?></strong></div>
    <div class="status"><span>🌐 URL storage :</span> <a href="<?= $testUrl ?>" target="_blank"><?= $testUrl ?></a></div>
</div>

<?php foreach ($messages as $msg): ?>
<div class="msg <?= str_starts_with($msg, '✅') ? 'ok' : (str_starts_with($msg, '❌') ? 'err' : 'warn') ?>">
    <?= htmlspecialchars($msg) ?>
</div>
<?php endforeach; ?>

<?php if (!$authenticated): ?>
<div class="card">
    <h3>Masukkan Password</h3>
    <form method="POST">
        <input type="password" name="password" placeholder="Password setup...">
        <input type="hidden" name="action" value="check">
        <button type="submit">Login</button>
    </form>
</div>
<?php else: ?>
<div class="card">
    <h3>Aksi</h3>
    <form method="POST" style="display:inline">
        <input type="hidden" name="password" value="<?= htmlspecialchars($pass) ?>">
        <input type="hidden" name="action" value="symlink">
        <button type="submit">🔗 Buat Symlink Storage</button>
    </form>
    <form method="POST" style="display:inline">
        <input type="hidden" name="password" value="<?= htmlspecialchars($pass) ?>">
        <input type="hidden" name="action" value="fix_permission">
        <button type="submit" class="danger">🔐 Fix Permission</button>
    </form>
    <hr style="margin:16px 0">
    <p style="font-size:.8rem;color:#6b7280">Setelah symlink dibuat, upload file dari lokal ke folder: <br>
    <code>/www/wwwroot/senjaweb.gauld.my.id/storage/app/public/</code></p>
</div>
<?php endif; ?>

<div class="card" style="background:#fff7ed;border:1px solid #fed7aa">
    <h4 style="color:#92400e;margin:0 0 8px">SSH Alternative</h4>
    <pre style="font-size:.8rem;background:#1e293b;color:#e2e8f0;padding:12px;border-radius:6px;overflow:auto">cd /www/wwwroot/senjaweb.gauld.my.id
php artisan storage:link
chmod -R 755 storage bootstrap/cache</pre>
</div>

</body>
</html>
