<?php

namespace App\Filament\Resources\GaleriWebsiteResource\Pages;

use App\Filament\Resources\GaleriWebsiteResource;
use Filament\Resources\Pages\CreateRecord;

class CreateGaleriWebsite extends CreateRecord
{
    protected static string $resource = GaleriWebsiteResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Jika sumber upload, gunakan path dari field upload
        if (($data['sumber'] ?? '') === 'upload' && ! empty($data['path_foto_upload'])) {
            $data['path_foto'] = $data['path_foto_upload'];
        }

        unset($data['path_foto_upload']);

        return $data;
    }
}
