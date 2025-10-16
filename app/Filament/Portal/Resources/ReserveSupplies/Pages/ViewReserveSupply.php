<?php

namespace App\Filament\Portal\Resources\ReserveSupplies\Pages;

use App\Filament\Portal\Resources\ReserveSupplies\ReserveSupplyResource;
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
