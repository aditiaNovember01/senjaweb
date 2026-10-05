<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasRoleBasedAccess;
use App\Filament\Resources\PeriodeKepengurusanResource\Pages;
use App\Models\PeriodeKepengurusan;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PeriodeKepengurusanResource extends Resource
{
    use HasRoleBasedAccess;
    protected static ?string $model = PeriodeKepengurusan::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?string $navigationLabel = 'Periode Kepengurusan';

    protected static ?string $modelLabel = 'Periode Kepengurusan';

    protected static ?string $pluralModelLabel = 'Periode Kepengurusan';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('nama')
                ->label('Nama Periode')
                ->placeholder('contoh: 2024/2025')
                ->required()
                ->maxLength(20),

            Toggle::make('is_aktif')
                ->label('Aktif')
                ->default(false),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')->label('Periode')->searchable()->sortable(),
                IconColumn::make('is_aktif')->label('Aktif')->boolean(),
                TextColumn::make('created_at')->label('Dibuat')->dateTime('d M Y')->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([EditAction::make()])
            ->bulkActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPeriodeKepengurusans::route('/'),
            'create' => Pages\CreatePeriodeKepengurusan::route('/create'),
            'edit'   => Pages\EditPeriodeKepengurusan::route('/{record}/edit'),
        ];
    }
}
