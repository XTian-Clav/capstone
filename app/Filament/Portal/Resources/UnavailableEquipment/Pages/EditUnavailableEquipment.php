<?php

namespace App\Filament\Portal\Resources\UnavailableEquipment\Pages;

use App\Filament\Portal\Resources\UnavailableEquipment\UnavailableEquipmentResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditUnavailableEquipment extends EditRecord
{
    protected static string $resource = UnavailableEquipmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
