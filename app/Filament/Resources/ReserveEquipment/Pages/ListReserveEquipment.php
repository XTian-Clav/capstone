<?php

namespace App\Filament\Resources\ReserveEquipment\Pages;

use App\Filament\Resources\ReserveEquipment\ReserveEquipmentResource;
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
