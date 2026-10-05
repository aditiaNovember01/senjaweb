<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasRoleBasedAccess;
use App\Filament\Resources\KategoriSuratResource\Pages;
use App\Models\KategoriSurat;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class KategoriSuratResource extends Resource
{
    use HasRoleBasedAccess;
    protected static ?string $model = KategoriSurat::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationGroup = 'Arsip Surat';

    protected static ?string $navigationLabel = 'Kategori Surat';

    protected static ?string $modelLabel = 'Kategori Surat';

    protected static ?string $pluralModelLabel = 'Kategori Surat';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('nama')
                ->label('Nama Kategori')
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(100)
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')->label('Kategori')->searchable()->sortable(),
                TextColumn::make('created_at')->label('Dibuat')->dateTime('d M Y')->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([EditAction::make()])
            ->bulkActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListKategoriSurats::route('/'),
            'create' => Pages\CreateKategoriSurat::route('/create'),
            'edit'   => Pages\EditKategoriSurat::route('/{record}/edit'),
        ];
    }
}
