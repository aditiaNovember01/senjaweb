<?php

namespace App\Filament\Resources\AngkatanResource\Pages;

use App\Filament\Resources\AngkatanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAngkatans extends ListRecords
{
    protected static string $resource = AngkatanResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
