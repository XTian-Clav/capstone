<?php

namespace App\Filament\Resources\Startups\Pages;

use App\Filament\Resources\Startups\StartupResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewStartup extends ViewRecord
{
    protected static string $resource = StartupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
