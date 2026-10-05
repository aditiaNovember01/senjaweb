<?php

namespace App\Filament\Resources\DivisiResource\Pages;

use App\Filament\Resources\DivisiResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDivisis extends ListRecords
{
    protected static string $resource = DivisiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
