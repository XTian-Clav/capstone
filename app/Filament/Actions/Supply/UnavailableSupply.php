<?php

namespace App\Filament\Actions\Supply;

use Filament\Actions\Action;
use Filament\Support\Enums\Size;
use App\Filament\Portal\Resources\UnavailableSupplies\UnavailableSupplyResource;

class UnavailableSupply extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'back')
            ->outlined()
            ->color('danger')
            ->size(Size::Small)
            ->label('Unavailable Supplies')
            ->icon('heroicon-o-no-symbol')
            ->url(fn (): string => UnavailableSupplyResource::getUrl('index'));
    }
}
