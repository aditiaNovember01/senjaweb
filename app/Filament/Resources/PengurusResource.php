<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasRoleBasedAccess;
use App\Filament\Resources\PengurusResource\Pages;
use App\Models\Anggota;
use App\Models\PeriodeKepengurusan;
use App\Models\Pengurus;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PengurusResource extends Resource
{
    use HasRoleBasedAccess;
    protected static ?string $model = Pengurus::class;

    protected static ?string $navigationIcon = 'heroicon-o-identification';

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?string $navigationLabel = 'Pengurus';

    protected static ?string $modelLabel = 'Pengurus';

    protected static ?string $pluralModelLabel = 'Data Pengurus';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('periode_id')
                ->label('Periode Kepengurusan')
                ->options(PeriodeKepengurusan::orderByDesc('nama')->pluck('nama', 'id'))
                ->required()
                ->searchable(),

            Select::make('anggota_id')
                ->label('Anggota')
                ->options(
                    Anggota::whereIn('status', ['Anggota Aktif', 'Pembina'])
                        ->orderBy('nama_lengkap')
                        ->get()
                        ->mapWithKeys(fn ($a) => [$a->id => "{$a->nama_lengkap} ({$a->nim})"])
                )
                ->required()
                ->searchable(),

            Select::make('jabatan')
                ->label('Jabatan')
                ->options(Pengurus::$jabatanOptions)
                ->required(),

            TextInput::make('urutan')
                ->label('Urutan Tampil')
                ->numeric()
                ->default(0)
                ->helperText('Angka kecil tampil lebih dulu'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('periode.nama')
                    ->label('Periode')
                    ->sortable(),

                TextColumn::make('jabatan')
                    ->label('Jabatan')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Pembina'             => 'gray',
                        'Ketua'               => 'primary',
                        'Wakil Ketua'         => 'primary',
                        'Steering Committee'  => 'warning',
                        'Sekretaris'          => 'info',
                        'Bendahara'           => 'info',
                        'Humas'               => 'success',
                        default               => 'secondary',
                    })
                    ->sortable(),

                TextColumn::make('anggota.nama_lengkap')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('anggota.nim')
                    ->label('NIM')
                    ->searchable(),

                TextColumn::make('anggota.divisi.nama')
                    ->label('Divisi')
                    ->sortable(),

                TextColumn::make('urutan')
                    ->label('Urutan')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('urutan')
            ->filters([
                SelectFilter::make('periode_id')
                    ->label('Periode')
                    ->options(PeriodeKepengurusan::orderByDesc('nama')->pluck('nama', 'id')),

                SelectFilter::make('jabatan')
                    ->label('Jabatan')
                    ->options(Pengurus::$jabatanOptions),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPengurus::route('/'),
            'create' => Pages\CreatePengurus::route('/create'),
            'edit'   => Pages\EditPengurus::route('/{record}/edit'),
        ];
    }
}
