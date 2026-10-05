<?php

namespace App\Filament\Widgets;

use App\Models\Kegiatan;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class KegiatanMendatangWidget extends BaseWidget
{
    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'full';

    protected static ?string $heading = 'Kegiatan Mendatang';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Kegiatan::query()
                    ->where('tanggal_mulai', '>=', now())
                    ->orderBy('tanggal_mulai')
                    ->limit(5)
            )
            ->columns([
                TextColumn::make('nama')->label('Kegiatan')->limit(40),
                TextColumn::make('tanggal_mulai')->label('Mulai')->dateTime('d M Y H:i'),
                TextColumn::make('lokasi')->label('Lokasi')->limit(30),
                TextColumn::make('divisi.nama')->label('Divisi'),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Akan Datang'        => 'info',
                        'Sedang Berlangsung' => 'warning',
                        default              => 'gray',
                    }),
            ]);
    }
}
