<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DendaPiketResource\Pages;
use App\Models\DendaPiket;
use App\Models\JadwalPiket;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class DendaPiketResource extends Resource
{
    protected static ?string $model = DendaPiket::class;

    protected static ?string $navigationIcon  = 'heroicon-o-banknotes';
    protected static ?string $navigationGroup = 'Piket';
    protected static ?string $navigationLabel = 'Denda Piket';
    protected static ?string $modelLabel      = 'Denda';
    protected static ?string $pluralModelLabel = 'Laporan Denda Piket';
    protected static ?int    $navigationSort  = 3;

    // ── Authorization ──────────────────────────────────────────────────────────

    public static function canViewAny(): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }

    public static function canCreate(): bool   { return false; } // dibuat otomatis
    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool   { return false; }
    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool { return auth()->user()?->isKetua() ?? false; }

    // ── Form (tidak dipakai tapi wajib ada) ───────────────────────────────────

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    // ── Table ─────────────────────────────────────────────────────────────────

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tanggal_piket')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('jadwalPiket.nama_hari')
                    ->label('Hari')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('anggota.nama_lengkap')
                    ->label('Anggota')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('anggota.nim')
                    ->label('NIM')
                    ->searchable(),

                TextColumn::make('alasan')
                    ->label('Alasan')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Tidak Hadir' => 'danger',
                        'Terlambat'   => 'warning',
                        default       => 'gray',
                    }),

                TextColumn::make('nominal_format')
                    ->label('Denda')
                    ->badge()
                    ->color('danger'),

                IconColumn::make('sudah_dibayar')
                    ->label('Lunas')
                    ->boolean()
                    ->trueColor('success')
                    ->falseColor('danger'),

                TextColumn::make('dibayar_at')
                    ->label('Dibayar')
                    ->dateTime('d M Y, H:i')
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('tanggal_piket', 'desc')
            ->filters([
                TernaryFilter::make('sudah_dibayar')
                    ->label('Status Bayar')
                    ->trueLabel('Sudah Lunas')
                    ->falseLabel('Belum Bayar'),

                SelectFilter::make('alasan')
                    ->label('Alasan')
                    ->options([
                        'Tidak Hadir' => 'Tidak Hadir',
                        'Terlambat'   => 'Terlambat',
                    ]),

                SelectFilter::make('jadwal_piket_id')
                    ->label('Hari')
                    ->options(
                        JadwalPiket::orderBy('hari_ke')
                            ->get()
                            ->mapWithKeys(fn ($j) => [$j->id => $j->nama_hari])
                    ),
            ])
            ->actions([
                // Tandai lunas per baris
                Action::make('lunas')
                    ->label('Tandai Lunas')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Tandai Denda Lunas?')
                    ->modalDescription(fn (DendaPiket $record) =>
                        "Denda {$record->nominal_format} untuk {$record->anggota->nama_lengkap} pada {$record->tanggal_piket->translatedFormat('d F Y')} akan ditandai sudah dibayar."
                    )
                    ->visible(fn (DendaPiket $record) => ! $record->sudah_dibayar)
                    ->action(function (DendaPiket $record): void {
                        $record->update([
                            'sudah_dibayar' => true,
                            'dibayar_at'    => now(),
                        ]);
                        Notification::make()->title('Denda ditandai lunas.')->success()->send();
                    }),

                // Batalkan lunas
                Action::make('batal_lunas')
                    ->label('Batalkan Lunas')
                    ->icon('heroicon-o-x-circle')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->visible(fn (DendaPiket $record) => $record->sudah_dibayar)
                    ->action(function (DendaPiket $record): void {
                        $record->update(['sudah_dibayar' => false, 'dibayar_at' => null]);
                        Notification::make()->title('Status lunas dibatalkan.')->warning()->send();
                    }),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    BulkAction::make('tandai_lunas_bulk')
                        ->label('Tandai Lunas')
                        ->icon('heroicon-o-check-badge')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function (Collection $records): void {
                            $records->each(fn ($r) => $r->update([
                                'sudah_dibayar' => true,
                                'dibayar_at'    => now(),
                            ]));
                            Notification::make()->title('Denda terpilih ditandai lunas.')->success()->send();
                        }),
                ]),
            ])
            ->headerActions([
                // Generate denda hari ini on-demand
                \Filament\Tables\Actions\Action::make('generate_denda')
                    ->label('Generate Denda Hari Ini')
                    ->icon('heroicon-o-arrow-path')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Generate Denda Tidak Hadir?')
                    ->modalDescription('Sistem akan memeriksa semua jadwal piket hari ini dan membuat denda Rp5.000 untuk anggota yang belum upload bukti setelah deadline.')
                    ->action(function (): void {
                        $jumlah = DendaPiket::generateHariIni();
                        Notification::make()
                            ->title("{$jumlah} denda baru berhasil dibuat.")
                            ->success()
                            ->send();
                    }),
            ]);
    }

    // ── Pages ─────────────────────────────────────────────────────────────────

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDendaPikets::route('/'),
        ];
    }
}
