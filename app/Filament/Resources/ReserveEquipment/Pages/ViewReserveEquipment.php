<?php

namespace App\Filament\Resources\ReserveEquipment\Pages;

use App\Filament\Resources\ReserveEquipment\ReserveEquipmentResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewReserveEquipment extends ViewRecord
{
    protected static string $resource = ReserveEquipmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
