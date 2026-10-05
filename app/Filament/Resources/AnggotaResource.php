<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasRoleBasedAccess;
use App\Filament\Resources\AnggotaResource\Pages;
use App\Models\Angkatan;
use App\Models\Anggota;
use App\Models\Divisi;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class AnggotaResource extends Resource
{
    use HasRoleBasedAccess;
    protected static ?string $model = Anggota::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Keanggotaan';

    protected static ?string $navigationLabel = 'Data Anggota';

    protected static ?string $modelLabel = 'Anggota';

    protected static ?string $pluralModelLabel = 'Data Anggota';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('nama_lengkap')
                ->label('Nama Lengkap')
                ->required()
                ->maxLength(100),

            TextInput::make('nim')
                ->label('NIM / ID')
                ->required()
                ->unique(ignoreRecord: true)
                ->helperText('Untuk Pembina, bisa diisi ID atau nomor lainnya.'),

            TextInput::make('email')
                ->label('Email')
                ->email()
                ->required()
                ->unique(ignoreRecord: true),

            TextInput::make('nomor_telepon')
                ->label('Nomor Telepon')
                ->tel()
                ->required()
                ->maxLength(20),

            // Status dipilih DULU — menentukan field lain yang muncul
            Select::make('status')
                ->label('Status Keanggotaan')
                ->options(Anggota::$statusOptions)
                ->required()
                ->default('Anggota Aktif')
                ->live() // reaktif — field di bawah berubah saat ini berubah
                ->columnSpanFull(),

            // Divisi — TIDAK tampil & TIDAK wajib jika Pembina
            Select::make('divisi_id')
                ->label('Divisi')
                ->options(Divisi::pluck('nama', 'id'))
                ->searchable()
                ->hidden(fn (Get $get): bool => $get('status') === 'Pembina')
                ->required(fn (Get $get): bool => $get('status') !== 'Pembina'),

            // Angkatan — TIDAK tampil & TIDAK wajib jika Pembina
            Select::make('angkatan_id')
                ->label('Angkatan')
                ->options(
                    Angkatan::orderByDesc('tahun')
                        ->get()
                        ->pluck('label', 'id')
                )
                ->searchable()
                ->hidden(fn (Get $get): bool => $get('status') === 'Pembina')
                ->required(fn (Get $get): bool => $get('status') !== 'Pembina'),

            // Tanggal Bergabung — TIDAK wajib jika Pembina
            DatePicker::make('tanggal_bergabung')
                ->label('Tanggal Bergabung')
                ->native(false)
                ->hidden(fn (Get $get): bool => $get('status') === 'Pembina')
                ->required(fn (Get $get): bool => $get('status') !== 'Pembina'),

            FileUpload::make('foto_profil')
                ->label('Foto Profil')
                ->image()
                ->imageResizeMode('cover')
                ->imageCropAspectRatio('1:1')
                ->maxSize(2048)
                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                ->directory('foto-profil')
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('foto_profil')
                    ->label('')
                    ->circular()
                    ->defaultImageUrl(fn ($record) =>
                        'https://ui-avatars.com/api/?name='.urlencode($record->nama_lengkap).'&background=EA580C&color=fff'
                    ),

                TextColumn::make('nama_lengkap')
                    ->label('Nama Lengkap')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('nim')
                    ->label('NIM / ID')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('divisi.nama')
                    ->label('Divisi')
                    ->sortable()
                    ->placeholder('—'),

                TextColumn::make('angkatan.nama')
                    ->label('Angkatan')
                    ->sortable()
                    ->placeholder('—'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Pembina'            => 'gray',
                        'Anggota Aktif'      => 'success',
                        'Anggota Pasif'      => 'warning',
                        'Anggota Kehormatan' => 'info',
                        default              => 'gray',
                    }),
            ])
            ->filters([
                SelectFilter::make('divisi_id')
                    ->label('Divisi')
                    ->options(Divisi::pluck('nama', 'id')),

                SelectFilter::make('status')
                    ->label('Status')
                    ->options(Anggota::$statusOptions),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListAnggotas::route('/'),
            'create' => Pages\CreateAnggota::route('/create'),
            'edit'   => Pages\EditAnggota::route('/{record}/edit'),
        ];
    }
}
