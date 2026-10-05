<?php

namespace App\Filament\Resources\GaleriWebsiteResource\Pages;

use App\Filament\Resources\GaleriWebsiteResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGaleriWebsites extends ListRecords
{
    protected static string $resource = GaleriWebsiteResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
