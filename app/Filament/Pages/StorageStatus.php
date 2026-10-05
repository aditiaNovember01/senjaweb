<?php

namespace App\Filament\Pages;

use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class StorageStatus extends Page
{
    protected static ?string $navigationIcon  = 'heroicon-o-server';
    protected static string  $view            = 'filament.pages.storage-status';
    protected static ?string $navigationLabel = 'Status Storage';
    protected static ?string $navigationGroup = 'Pengaturan';
    protected static ?int    $navigationSort  = 100;

    public static function canAccess(): bool
    {
        return auth()->user()?->isSuperAdmin() ?? false;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('fix_storage')
                ->label('🔧 Fix Storage Symlink')
                ->color('warning')
                ->requiresConfirmation()
                ->modalHeading('Jalankan Storage Fix?')
                ->modalDescription('Akan membuat symlink public/storage → storage/app/public dan memperbaiki permission.')
                ->action(function () {
                    try {
                        Artisan::call('storage:fix');
                        $output = Artisan::output();
                        Notification::make()
                            ->title('Storage fix selesai')
                            ->body(nl2br(strip_tags($output)))
                            ->success()
                            ->send();
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Error: ' . $e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),

            Action::make('storage_link')
                ->label('🔗 Artisan Storage:Link')
                ->color('primary')
                ->requiresConfirmation()
                ->action(function () {
                    try {
                        Artisan::call('storage:link');
                        Notification::make()->title('storage:link berhasil dijalankan.')->success()->send();
                    } catch (\Exception $e) {
                        Notification::make()->title('Error: ' . $e->getMessage())->danger()->send();
                    }
                }),

            Action::make('clear_cache')
                ->label('🗑️ Clear Cache')
                ->color('gray')
                ->action(function () {
                    Artisan::call('optimize:clear');
                    Notification::make()->title('Cache berhasil dibersihkan.')->success()->send();
                }),
        ];
    }

    public function getStorageInfo(): array
    {
        $publicStorage = public_path('storage');
        $storageApp    = storage_path('app/public');

        $subdirs = [];
        if (is_dir($storageApp)) {
            foreach (File::directories($storageApp) as $dir) {
                $name   = basename($dir);
                $count  = count(File::files($dir));
                $size   = 0;
                foreach (File::files($dir) as $f) {
                    $size += $f->getSize();
                }
                $subdirs[] = [
                    'name'  => $name,
                    'count' => $count,
                    'size'  => $this->formatBytes($size),
                ];
            }
        }

        return [
            'symlink_exists'  => is_link($publicStorage),
            'symlink_target'  => is_link($publicStorage) ? readlink($publicStorage) : null,
            'storage_exists'  => is_dir($storageApp),
            'public_url'      => config('app.url') . '/storage',
            'app_timezone'    => config('app.timezone'),
            'app_env'         => config('app.env'),
            'php_version'     => PHP_VERSION,
            'subdirs'         => $subdirs,
        ];
    }

    private function formatBytes(int $bytes): string
    {
        if ($bytes < 1024) return "{$bytes} B";
        if ($bytes < 1048576) return round($bytes / 1024, 1) . ' KB';
        return round($bytes / 1048576, 1) . ' MB';
    }
}
