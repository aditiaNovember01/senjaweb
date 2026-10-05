<?php

namespace App\Filament\Widgets;

use App\Models\PendaftaranAnggota;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class PendaftaranTerbaruWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    protected static ?string $heading = 'Pendaftaran Anggota Terbaru';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                PendaftaranAnggota::query()
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                TextColumn::make('nama_lengkap')->label('Nama'),
                TextColumn::make('nim')->label('NIM'),
                TextColumn::make('divisi.nama')->label('Divisi'),
                TextColumn::make('angkatan')->label('Angkatan'),
                TextColumn::make('created_at')->label('Daftar')->dateTime('d M Y'),
            ]);
    }
}
