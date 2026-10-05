<?php

namespace App\Filament\Resources\PeriodeKepengurusanResource\Pages;

use App\Filament\Resources\PeriodeKepengurusanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPeriodeKepengurusans extends ListRecords
{
    protected static string $resource = PeriodeKepengurusanResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
