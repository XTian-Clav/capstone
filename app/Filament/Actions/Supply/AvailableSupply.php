<?php

namespace App\Filament\Actions\Supply;

use Filament\Actions\Action;
use Filament\Support\Enums\Size;
use App\Filament\Portal\Resources\Supplies\SupplyResource;

class AvailableSupply extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'back')
            ->outlined()
            ->color('success')
            ->size(Size::Small)
            ->label('Available Supplies')
            ->icon('heroicon-o-check')
            ->url(fn (): string => SupplyResource::getUrl('index'));
    }
}
