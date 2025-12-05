<?php

namespace App\Filament\Actions\Equipment;

use Filament\Actions\Action;
use Filament\Support\Enums\Size;
use App\Filament\Portal\Resources\UnavailableEquipment\UnavailableEquipmentResource;

class UnavailableEquipment extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'back')
            ->outlined()
            ->color('danger')
            ->size(Size::Small)
            ->icon('heroicon-o-no-symbol')
            ->label('Unavailable Equipment')
            ->url(fn (): string => UnavailableEquipmentResource::getUrl('index'));
    }
}
