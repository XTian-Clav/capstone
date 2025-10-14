<?php

namespace App\Filament\Resources\ReserveSupplies\Pages;

use App\Filament\Resources\ReserveSupplies\ReserveSupplyResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewReserveSupply extends ViewRecord
{
    protected static string $resource = ReserveSupplyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
