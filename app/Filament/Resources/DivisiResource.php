<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasRoleBasedAccess;
use App\Filament\Resources\DivisiResource\Pages;
use App\Models\Divisi;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DivisiResource extends Resource
{
    use HasRoleBasedAccess;
    protected static ?string $model = Divisi::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?string $navigationLabel = 'Divisi';

    protected static ?string $modelLabel = 'Divisi';

    protected static ?string $pluralModelLabel = 'Divisi';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('nama')
                ->label('Nama Divisi')
                ->required()
                ->maxLength(100)
                ->columnSpanFull(),

            Textarea::make('deskripsi')
                ->label('Deskripsi')
                ->rows(3)
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')
                    ->label('Nama Divisi')
                    ->searchable()
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
            ->actions([
                EditAction::make(),
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
            'index'  => Pages\ListDivisis::route('/'),
            'create' => Pages\CreateDivisi::route('/create'),
            'edit'   => Pages\EditDivisi::route('/{record}/edit'),
        ];
    }
}
