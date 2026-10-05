<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasRoleBasedAccess;
use App\Filament\Resources\ProgramKerjaResource\Pages;
use App\Models\Anggota;
use App\Models\Divisi;
use App\Models\PeriodeKepengurusan;
use App\Models\ProgramKerja;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
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

class ProgramKerjaResource extends Resource
{
    use HasRoleBasedAccess;
    protected static ?string $model = ProgramKerja::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationGroup = 'Kegiatan & Proker';

    protected static ?string $navigationLabel = 'Program Kerja';

    protected static ?string $modelLabel = 'Program Kerja';

    protected static ?string $pluralModelLabel = 'Program Kerja';

    protected static ?int $navigationSort = 2;

    public static function getDivisiOptions(): array
    {
        return Divisi::orderBy('nama')->pluck('nama', 'id')->toArray();
    }

    public static function getPeriodeOptions(): array
    {
        return PeriodeKepengurusan::orderBy('nama')->pluck('nama', 'id')->toArray();
    }

    public static function getAnggotaOptions(): array
    {
        return Anggota::orderBy('nama_lengkap')->pluck('nama_lengkap', 'id')->toArray();
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('nama')
                ->label('Nama Program Kerja')
                ->required()
                ->maxLength(150)
                ->columnSpanFull(),

            Textarea::make('deskripsi')
                ->label('Deskripsi')
                ->required()
                ->rows(4)
                ->columnSpanFull(),

            Select::make('divisi_id')
                ->label('Divisi Penanggung Jawab')
                ->options(static::getDivisiOptions())
                ->required(),

            Select::make('ketua_pelaksana_id')
                ->label('Ketua Pelaksana / Penanggung Jawab')
                ->searchable()
                ->getSearchResultsUsing(fn (string $search): array => Anggota::query()
                    ->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('nim', 'like', "%{$search}%")
                    ->limit(50)
                    ->pluck('nama_lengkap', 'id')
                    ->toArray())
                ->getOptionLabelUsing(fn ($value): ?string => Anggota::find($value)?->nama_lengkap)
                ->nullable()
                ->helperText('Ketik nama atau NIM anggota untuk mencari.'),

            Select::make('periode_id')
                ->label('Periode Kepengurusan')
                ->options(static::getPeriodeOptions())
                ->required(),

            DatePicker::make('tanggal_mulai_estimasi')
                ->label('Estimasi Mulai')
                ->required()
                ->native(true),

            DatePicker::make('tanggal_selesai_estimasi')
                ->label('Estimasi Selesai')
                ->required()
                ->native(true),

            Select::make('status')
                ->label('Status')
                ->options([
                    'Direncanakan'    => 'Direncanakan',
                    'Sedang Berjalan' => 'Sedang Berjalan',
                    'Selesai'         => 'Selesai',
                    'Dibatalkan'      => 'Dibatalkan',
                ])
                ->required()
                ->default('Direncanakan'),

            TextInput::make('target_peserta')
                ->label('Target Peserta')
                ->numeric()
                ->minValue(1)
                ->nullable(),

            TextInput::make('estimasi_anggaran')
                ->label('Estimasi Anggaran (Rp)')
                ->numeric()
                ->minValue(0)
                ->nullable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')
                    ->label('Nama Proker')
                    ->searchable()
                    ->sortable()
                    ->limit(40),
                TextColumn::make('divisi.nama')
                    ->label('Divisi')
                    ->sortable(),
                TextColumn::make('ketuaPelaksana.nama_lengkap')
                    ->label('Ketua Pelaksana')
                    ->sortable()
                    ->placeholder('—'),
                TextColumn::make('periode.nama')
                    ->label('Periode')
                    ->sortable(),
                TextColumn::make('tanggal_mulai_estimasi')
                    ->label('Mulai')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('tanggal_selesai_estimasi')
                    ->label('Selesai')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Direncanakan'    => 'info',
                        'Sedang Berjalan' => 'warning',
                        'Selesai'         => 'success',
                        'Dibatalkan'      => 'danger',
                        default           => 'gray',
                    }),
            ])
            ->filters([
                SelectFilter::make('divisi_id')
                    ->label('Divisi')
                    ->options(static::getDivisiOptions()),
                SelectFilter::make('periode_id')
                    ->label('Periode')
                    ->options(static::getPeriodeOptions()),
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'Direncanakan'    => 'Direncanakan',
                        'Sedang Berjalan' => 'Sedang Berjalan',
                        'Selesai'         => 'Selesai',
                        'Dibatalkan'      => 'Dibatalkan',
                    ]),
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
            'index'  => Pages\ListProgramKerjas::route('/'),
            'create' => Pages\CreateProgramKerja::route('/create'),
            'edit'   => Pages\EditProgramKerja::route('/{record}/edit'),
        ];
    }
}
