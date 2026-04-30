<?php

namespace App\Filament\Resources\SiteInfoResource\Pages;

use App\Filament\Resources\SiteInfoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSiteInfo extends EditRecord
{
    protected static string $resource = SiteInfoResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function getBreadcrumbs(): array
    {
        return [
            '#' => 'Configuración General',
        ];
    }
}
