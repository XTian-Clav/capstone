<?php

namespace App\Filament\Portal\Resources\ReserveEquipment\Pages;

use App\Filament\Portal\Resources\ReserveEquipment\ReserveEquipmentResource;
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
