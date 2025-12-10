<?php

namespace App\Filament\Portal\Resources\ReserveEquipment\Pages;

use Filament\Resources\Pages\Page;
use App\Filament\Actions\BackButton;
use App\Filament\Portal\Resources\ReserveEquipment\ReserveEquipmentResource;

class PrintEquipment extends Page
{
    protected static string $resource = ReserveEquipmentResource::class;

    protected string $view = 'filament.portal.resources.reserve-equipment.pages.print-equipment';

    protected function getHeaderActions(): array
    {
        return [
            BackButton::make(),
        ];
    }
}