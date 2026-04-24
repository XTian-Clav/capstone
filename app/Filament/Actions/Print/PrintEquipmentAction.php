<?php

namespace App\Filament\Actions\Print;

use Filament\Actions\Action;
use App\Filament\Portal\Resources\ReserveEquipment\Pages\PrintEquipment;

class PrintEquipmentAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'back')
            ->color('primary')
            ->label('Print')
            ->icon('heroicon-s-printer');
    }
}
