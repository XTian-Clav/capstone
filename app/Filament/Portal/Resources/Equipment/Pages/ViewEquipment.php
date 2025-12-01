<?php

namespace App\Filament\Portal\Resources\Equipment\Pages;

use Filament\Actions\Action;
use Filament\Actions\EditAction;
use App\Filament\Actions\BackButton;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Portal\Resources\Equipment\EquipmentResource;

class ViewEquipment extends ViewRecord
{
    protected static string $resource = EquipmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            BackButton::make(),
            EditAction::make(),
        ];
    }
}
