<?php

namespace App\Filament\Portal\Resources\ReserveEquipment\Pages;

use App\Filament\Portal\Resources\ReserveEquipment\ReserveEquipmentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListReserveEquipment extends ListRecords
{
    protected static string $resource = ReserveEquipmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
