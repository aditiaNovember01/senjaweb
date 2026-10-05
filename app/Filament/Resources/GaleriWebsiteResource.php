<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasRoleBasedAccess;
use App\Filament\Resources\GaleriWebsiteResource\Pages;
use App\Models\GaleriWebsite;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class GaleriWebsiteResource extends Resource
{
    use HasRoleBasedAccess;

    // Override: only super_admin and ketua can see/manage website gallery
    public static function canViewAny(): bool
    {
        return auth()->user()?->canManageSiteSettings() ?? false;
    }
    protected static ?string $model = GaleriWebsite::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationGroup = 'Pengaturan';

    protected static ?string $navigationLabel = 'Galeri Website';

    protected static ?string $modelLabel = 'Foto Galeri';

    protected static ?string $pluralModelLabel = 'Galeri Website';

    protected static ?int $navigationSort = 98;

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('judul')
                ->label('Judul / Keterangan Foto')
                ->maxLength(150)
                ->nullable()
                ->columnSpanFull(),

            Select::make('sumber')
                ->label('Sumber Foto')
                ->options([
                    'upload' => 'Upload Baru',
                    'assets' => 'File dari assets/galleryfoto',
                ])
                ->default('upload')
                ->required()
                ->live(),

            // Upload baru
            FileUpload::make('path_foto_upload')
                ->label('Upload Foto')
                ->image()
                ->maxSize(5120)
                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                ->directory('galeri-website')
                ->visible(fn ($get) => $get('sumber') === 'upload')
                ->columnSpanFull(),

            // Path dari assets
            TextInput::make('path_foto')
                ->label('Path Foto (relatif dari public/)')
                ->placeholder('assets/galleryfoto/foto1.jpeg')
                ->helperText('Contoh: assets/galleryfoto/nama-file.jpeg')
                ->visible(fn ($get) => $get('sumber') === 'assets')
                ->columnSpanFull(),

            Textarea::make('keterangan')
                ->label('Keterangan')
                ->rows(3)
                ->maxLength(500)
                ->nullable()
                ->columnSpanFull(),

            TextInput::make('urutan')
                ->label('Urutan Tampil')
                ->numeric()
                ->default(0)
                ->helperText('Angka kecil tampil lebih dulu'),

            Toggle::make('is_aktif')
                ->label('Aktifkan')
                ->default(true),

            Toggle::make('is_beranda')
                ->label('Tampilkan di Beranda')
                ->helperText('Hanya 1 foto yang tampil di beranda. Jika lebih dari 1 diaktifkan, yang paling baru akan tampil.')
                ->default(false)
                ->live(),

            TextInput::make('tag_beranda')
                ->label('Tag Foto Beranda')
                ->placeholder('Pengurus UKM SENJA 2026-2027')
                ->maxLength(100)
                ->helperText('Teks tag yang tampil di beranda (opsional).')
                ->visible(fn ($get) => $get('is_beranda')),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('url_foto')
                    ->label('Foto')
                    ->width(80)
                    ->height(60)
                    ->getStateUsing(fn (GaleriWebsite $record) => $record->url_foto),

                TextColumn::make('judul')
                    ->label('Judul')
                    ->searchable()
                    ->limit(40)
                    ->placeholder('—'),

                TextColumn::make('sumber')
                    ->label('Sumber')
                    ->badge()
                    ->color(fn (string $state) => $state === 'assets' ? 'info' : 'success'),

                TextColumn::make('urutan')
                    ->label('Urutan')
                    ->sortable(),

                IconColumn::make('is_aktif')
                    ->label('Aktif')
                    ->boolean(),

                IconColumn::make('is_beranda')
                    ->label('Beranda')
                    ->boolean()
                    ->trueColor('warning')
                    ->falseColor('gray'),
            ])
            ->defaultSort('urutan')
            ->reorderable('urutan')
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
            'index'  => Pages\ListGaleriWebsites::route('/'),
            'create' => Pages\CreateGaleriWebsite::route('/create'),
            'edit'   => Pages\EditGaleriWebsite::route('/{record}/edit'),
        ];
    }
}
