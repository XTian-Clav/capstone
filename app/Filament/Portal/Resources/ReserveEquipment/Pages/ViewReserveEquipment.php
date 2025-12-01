<?php

namespace App\Filament\Portal\Resources\ReserveEquipment\Pages;

use Filament\Actions\EditAction;
use App\Filament\Actions\BackButton;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Actions\Equipment\RejectEquipmentAction;
use App\Filament\Actions\Equipment\ApproveEquipmentAction;
use App\Filament\Actions\Equipment\CompleteEquipmentAction;
use App\Filament\Portal\Resources\ReserveEquipment\ReserveEquipmentResource;

class ViewReserveEquipment extends ViewRecord
{
    protected static string $resource = ReserveEquipmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            BackButton::make(),
            ApproveEquipmentAction::make(),
            RejectEquipmentAction::make(),
            CompleteEquipmentAction::make(),
        ];
    }
}
