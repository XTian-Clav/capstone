<?php

namespace App\Filament\Actions\Equipment;

use Filament\Actions\Action;
use Filament\Support\Enums\Size;
use App\Filament\Portal\Resources\Equipment\EquipmentResource;

class AvailableEquipment extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'back')
            ->outlined()
            ->color('success')
            ->size(Size::Small)
            ->icon('heroicon-o-check')
            ->label('Available Equipment')
            ->url(fn (): string => EquipmentResource::getUrl('index'));
    }
}