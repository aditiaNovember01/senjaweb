<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class StorageFix extends Command
{
    protected $signature   = 'storage:fix';
    protected $description = 'Diagnose dan perbaiki symlink storage untuk shared hosting (aaPanel, cPanel, dll).';

    public function handle(): int
    {
        $this->info('=== Storage Fix Tool ===');
        $this->newLine();

        $publicStorage = public_path('storage');
        $storageApp    = storage_path('app/public');

        // 1. Cek folder storage/app/public
        $this->line("📁 storage/app/public : {$storageApp}");
        if (! is_dir($storageApp)) {
            $this->warn('   ❌ Folder tidak ada — membuat...');
            File::makeDirectory($storageApp, 0755, true);
            $this->info('   ✅ Folder dibuat.');
        } else {
            $this->info('   ✅ Folder ada.');
        }

        // 2. Cek symlink public/storage
        $this->line("🔗 public/storage symlink : {$publicStorage}");
        if (is_link($publicStorage)) {
            $target = readlink($publicStorage);
            $this->info("   ✅ Symlink ada → {$target}");
        } elseif (is_dir($publicStorage)) {
            $this->warn('   ⚠️  Ada folder biasa (bukan symlink) — menghapus dan membuat symlink...');
            File::deleteDirectory($publicStorage);
            symlink($storageApp, $publicStorage);
            $this->info('   ✅ Symlink dibuat.');
        } else {
            $this->warn('   ❌ Symlink tidak ada — membuat...');
            try {
                symlink($storageApp, $publicStorage);
                $this->info('   ✅ Symlink dibuat.');
            } catch (\Exception $e) {
                $this->error('   ❌ Gagal buat symlink: ' . $e->getMessage());
                $this->line('   💡 Coba jalankan: php artisan storage:link');
            }
        }

        // 3. Cek permission
        $this->newLine();
        $this->line('🔐 Permissions:');
        $perms = [
            storage_path()             => '755',
            storage_path('app')        => '755',
            storage_path('app/public') => '755',
            storage_path('logs')       => '755',
            base_path('bootstrap/cache') => '755',
        ];
        foreach ($perms as $path => $required) {
            if (is_dir($path)) {
                $current = substr(sprintf('%o', fileperms($path)), -4);
                $ok = octdec($current) >= octdec($required);
                $icon = $ok ? '✅' : '⚠️ ';
                $this->line("   {$icon} {$path} → {$current}");
                if (! $ok) {
                    chmod($path, octdec('0755'));
                    $this->info('      → Fixed to 0755');
                }
            }
        }

        // 4. Ringkasan
        $this->newLine();
        $this->info('=== Selesai ===');
        $this->line('Jalankan juga: php artisan config:clear && php artisan cache:clear');

        return self::SUCCESS;
    }
}
