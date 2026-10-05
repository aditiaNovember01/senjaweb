<?php

namespace App\Filament\Widgets;

use App\Models\Anggota;
use App\Models\PendaftaranAnggota;
use App\Models\ProgramKerja;
use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Anggota Aktif', Anggota::where('status', 'Anggota Aktif')->count())
                ->description('Anggota Aktif terdaftar')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),

            Stat::make('Total Anggota', Anggota::count())
                ->description('Aktif + Pasif + Kehormatan + Pembina')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info'),

            Stat::make('Surat Masuk', SuratMasuk::count())
                ->description('Total surat masuk terarsip')
                ->descriptionIcon('heroicon-m-inbox-arrow-down')
                ->color('info'),

            Stat::make('Surat Keluar', SuratKeluar::count())
                ->description('Total surat keluar terarsip')
                ->descriptionIcon('heroicon-m-paper-airplane')
                ->color('warning'),

            Stat::make('Proker Aktif', ProgramKerja::whereIn('status', ['Direncanakan', 'Sedang Berjalan'])->count())
                ->description('Proker direncanakan & berjalan')
                ->descriptionIcon('heroicon-m-clipboard-document-check')
                ->color('primary'),

            Stat::make('Menunggu Verifikasi', PendaftaranAnggota::count())
                ->description('Pendaftaran anggota baru')
                ->descriptionIcon('heroicon-m-clock')
                ->color('danger'),
        ];
    }
}
