<?php

namespace App\Filament\Portal\Resources\Equipment\Pages;

use App\Filament\Portal\Resources\Equipment\EquipmentResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewEquipment extends ViewRecord
{
    protected static string $resource = EquipmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url(static::getResource()::getUrl('index')),

            EditAction::make(),
        ];
    }
}
