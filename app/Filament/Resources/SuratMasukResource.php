<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasRoleBasedAccess;
use App\Filament\Resources\SuratMasukResource\Pages;
use App\Models\KategoriSurat;
use App\Models\SuratMasuk;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;

class SuratMasukResource extends Resource
{
    use HasRoleBasedAccess;
    protected static ?string $model = SuratMasuk::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox-arrow-down';

    protected static ?string $navigationGroup = 'Arsip Surat';

    protected static ?string $navigationLabel = 'Surat Masuk';

    protected static ?string $modelLabel = 'Surat Masuk';

    protected static ?string $pluralModelLabel = 'Surat Masuk';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('nomor_surat')
                ->label('Nomor Surat')
                ->required()
                ->maxLength(50)
                ->unique(ignoreRecord: true),

            DatePicker::make('tanggal_surat')
                ->label('Tanggal Surat')
                ->required()
                ->native(false),

            DatePicker::make('tanggal_diterima')
                ->label('Tanggal Diterima')
                ->required()
                ->native(false),

            TextInput::make('nama_pengirim')
                ->label('Nama Pengirim')
                ->required()
                ->maxLength(100),

            TextInput::make('perihal')
                ->label('Perihal')
                ->required()
                ->maxLength(255)
                ->columnSpanFull(),

            Select::make('kategori_id')
                ->label('Kategori')
                ->options(KategoriSurat::pluck('nama', 'id'))
                ->required()
                ->searchable(),

            FileUpload::make('berkas_pdf')
                ->label('Berkas PDF')
                ->acceptedFileTypes(['application/pdf'])
                ->maxSize(10240) // 10 MB
                ->directory('surat-masuk')
                ->columnSpanFull(),
        ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            TextEntry::make('nomor_surat')->label('Nomor Surat'),
            TextEntry::make('tanggal_surat')->label('Tanggal Surat')->date('d M Y'),
            TextEntry::make('tanggal_diterima')->label('Tanggal Diterima')->date('d M Y'),
            TextEntry::make('nama_pengirim')->label('Pengirim'),
            TextEntry::make('perihal')->label('Perihal')->columnSpanFull(),
            TextEntry::make('kategori.nama')->label('Kategori'),
            TextEntry::make('berkas_pdf')->label('Berkas')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nomor_surat')->label('Nomor Surat')->searchable()->sortable(),
                TextColumn::make('tanggal_surat')->label('Tgl Surat')->date('d M Y')->sortable(),
                TextColumn::make('tanggal_diterima')->label('Tgl Diterima')->date('d M Y')->sortable(),
                TextColumn::make('nama_pengirim')->label('Pengirim')->searchable(),
                TextColumn::make('perihal')->label('Perihal')->searchable()->limit(40),
                TextColumn::make('kategori.nama')->label('Kategori')->sortable(),
            ])
            ->filters([
                SelectFilter::make('kategori_id')
                    ->label('Kategori')
                    ->options(KategoriSurat::pluck('nama', 'id')),
            ])
            ->actions([
                ViewAction::make(),
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
            'index'  => Pages\ListSuratMasuks::route('/'),
            'create' => Pages\CreateSuratMasuk::route('/create'),
            'edit'   => Pages\EditSuratMasuk::route('/{record}/edit'),
        ];
    }
}
