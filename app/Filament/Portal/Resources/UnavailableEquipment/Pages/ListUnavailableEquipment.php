<?php

namespace App\Filament\Portal\Resources\UnavailableEquipment\Pages;

use App\Filament\Portal\Resources\UnavailableEquipment\UnavailableEquipmentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListUnavailableEquipment extends ListRecords
{
    protected static string $resource = UnavailableEquipmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Add Unavailable Equipment')->icon('heroicon-o-plus'),
        ];
    }
}
