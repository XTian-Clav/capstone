<?php

namespace App\Filament\Portal\Resources\UnavailableEquipment\Pages;

use App\Filament\Portal\Resources\UnavailableEquipment\UnavailableEquipmentResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewUnavailableEquipment extends ViewRecord
{
    protected static string $resource = UnavailableEquipmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
