<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasRoleBasedAccess;
use App\Filament\Resources\AngkatanResource\Pages;
use App\Models\Angkatan;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AngkatanResource extends Resource
{
    use HasRoleBasedAccess;
    protected static ?string $model = Angkatan::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?string $navigationLabel = 'Angkatan';

    protected static ?string $modelLabel = 'Angkatan';

    protected static ?string $pluralModelLabel = 'Angkatan';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('nama')
                ->label('Nama Angkatan')
                ->placeholder('contoh: Angkatan Garuda')
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(100),

            TextInput::make('tahun')
                ->label('Tahun Masuk')
                ->placeholder('contoh: 2022')
                ->numeric()
                ->required()
                ->minValue(2000)
                ->maxValue(now()->year),

            Textarea::make('deskripsi')
                ->label('Deskripsi')
                ->rows(2)
                ->nullable()
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')
                    ->label('Nama Angkatan')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('tahun')
                    ->label('Tahun Masuk')
                    ->sortable(),

                TextColumn::make('anggotas_count')
                    ->label('Jumlah Anggota')
                    ->counts('anggotas')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('tahun', 'desc')
            ->actions([EditAction::make()])
            ->bulkActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListAngkatans::route('/'),
            'create' => Pages\CreateAngkatan::route('/create'),
            'edit'   => Pages\EditAngkatan::route('/{record}/edit'),
        ];
    }
}
