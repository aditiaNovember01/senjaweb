<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasRoleBasedAccess;
use App\Filament\Resources\KegiatanResource\Pages;
use App\Models\Divisi;
use App\Models\Kegiatan;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class KegiatanResource extends Resource
{
    use HasRoleBasedAccess;
    protected static ?string $model = Kegiatan::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $navigationGroup = 'Kegiatan & Proker';

    protected static ?string $navigationLabel = 'Kegiatan';

    protected static ?string $modelLabel = 'Kegiatan';

    protected static ?string $pluralModelLabel = 'Kegiatan';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('nama')
                ->label('Nama Kegiatan')
                ->required()
                ->maxLength(150)
                ->columnSpanFull(),

            Textarea::make('deskripsi')
                ->label('Deskripsi')
                ->required()
                ->rows(4)
                ->columnSpanFull(),

            DateTimePicker::make('tanggal_mulai')
                ->label('Tanggal Mulai')
                ->required()
                ->native(false),

            DateTimePicker::make('tanggal_selesai')
                ->label('Tanggal Selesai')
                ->required()
                ->native(false)
                ->after('tanggal_mulai'),

            TextInput::make('lokasi')
                ->label('Lokasi')
                ->required()
                ->maxLength(255),

            Select::make('divisi_id')
                ->label('Divisi Penyelenggara')
                ->options(Divisi::pluck('nama', 'id'))
                ->required()
                ->searchable(),

            Select::make('status')
                ->label('Status')
                ->options([
                    'Akan Datang'       => 'Akan Datang',
                    'Sedang Berlangsung' => 'Sedang Berlangsung',
                    'Selesai'           => 'Selesai',
                    'Dibatalkan'        => 'Dibatalkan',
                ])
                ->required()
                ->default('Akan Datang'),

            FileUpload::make('gambar_poster')
                ->label('Gambar Poster')
                ->image()
                ->maxSize(5120) // 5 MB
                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                ->directory('poster-kegiatan')
                ->columnSpanFull(),

            Repeater::make('galeriFotos')
                ->label('Galeri Foto')
                ->relationship()
                ->schema([
                    FileUpload::make('path_foto')
                        ->label('Foto')
                        ->image()
                        ->maxSize(5120)
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                        ->directory('galeri-kegiatan')
                        ->required(),
                ])
                ->addActionLabel('Tambah Foto')
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('gambar_poster')
                    ->label('')
                    ->height(48)
                    ->width(64),

                TextColumn::make('nama')->label('Nama Kegiatan')->searchable()->sortable()->limit(40),
                TextColumn::make('tanggal_mulai')->label('Mulai')->dateTime('d M Y H:i')->sortable(),
                TextColumn::make('lokasi')->label('Lokasi')->limit(30),
                TextColumn::make('divisi.nama')->label('Divisi')->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Akan Datang'        => 'info',
                        'Sedang Berlangsung' => 'warning',
                        'Selesai'            => 'success',
                        'Dibatalkan'         => 'danger',
                        default              => 'gray',
                    }),
            ])
            ->filters([
                SelectFilter::make('divisi_id')->label('Divisi')->options(Divisi::pluck('nama', 'id')),
                SelectFilter::make('status')->label('Status')->options([
                    'Akan Datang'        => 'Akan Datang',
                    'Sedang Berlangsung' => 'Sedang Berlangsung',
                    'Selesai'            => 'Selesai',
                    'Dibatalkan'         => 'Dibatalkan',
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
            'index'  => Pages\ListKegiatans::route('/'),
            'create' => Pages\CreateKegiatan::route('/create'),
            'edit'   => Pages\EditKegiatan::route('/{record}/edit'),
        ];
    }
}
