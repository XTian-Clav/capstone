<?php

namespace App\Filament\Actions\Print;

use Filament\Actions\Action;
use App\Filament\Portal\Resources\ReserveSupplies\Pages\PrintSupply;

class ViewSupplyAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'back')
            ->color('info')
            ->label('Print')
            ->icon('heroicon-s-document-text')
            ->url(fn ($record) => PrintSupply::getUrl(['record' => $record->id]))
            ->openUrlInNewTab();
    }
}
