<?php

namespace App\Filament\Actions\Print;

use Filament\Actions\Action;
use App\Filament\Portal\Resources\ReserveEquipment\Pages\PrintEquipment;

class ViewEquipmentAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'back')
            ->color('info')
            ->label('Print')
            ->icon('heroicon-s-document-text')
            ->url(fn ($record) => PrintEquipment::getUrl(['record' => $record->id]));
    }
}
