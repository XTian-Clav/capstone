<?php

namespace App\Filament\Actions\Print;

use Filament\Actions\Action;
use App\Filament\Portal\Resources\ReserveSupplies\Pages\PrintSupply;

class PrintSupplyAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'back')
            ->color('primary')
            ->label('Print')
            ->icon('heroicon-s-printer');
    }
}
