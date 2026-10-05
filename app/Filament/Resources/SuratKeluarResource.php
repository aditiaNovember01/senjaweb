<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasRoleBasedAccess;
use App\Filament\Resources\SuratKeluarResource\Pages;
use App\Models\KategoriSurat;
use App\Models\SuratKeluar;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SuratKeluarResource extends Resource
{
    use HasRoleBasedAccess;
    protected static ?string $model = SuratKeluar::class;

    protected static ?string $navigationIcon = 'heroicon-o-paper-airplane';

    protected static ?string $navigationGroup = 'Arsip Surat';

    protected static ?string $navigationLabel = 'Surat Keluar';

    protected static ?string $modelLabel = 'Surat Keluar';

    protected static ?string $pluralModelLabel = 'Surat Keluar';

    protected static ?int $navigationSort = 2;

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

            DatePicker::make('tanggal_dikirim')
                ->label('Tanggal Dikirim')
                ->required()
                ->native(false),

            TextInput::make('nama_penerima')
                ->label('Nama Penerima')
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
                ->maxSize(10240)
                ->directory('surat-keluar')
                ->columnSpanFull(),
        ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            TextEntry::make('nomor_surat')->label('Nomor Surat'),
            TextEntry::make('tanggal_surat')->label('Tanggal Surat')->date('d M Y'),
            TextEntry::make('tanggal_dikirim')->label('Tanggal Dikirim')->date('d M Y'),
            TextEntry::make('nama_penerima')->label('Penerima'),
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
                TextColumn::make('tanggal_dikirim')->label('Tgl Dikirim')->date('d M Y')->sortable(),
                TextColumn::make('nama_penerima')->label('Penerima')->searchable(),
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
            'index'  => Pages\ListSuratKeluars::route('/'),
            'create' => Pages\CreateSuratKeluar::route('/create'),
            'edit'   => Pages\EditSuratKeluar::route('/{record}/edit'),
        ];
    }
}
