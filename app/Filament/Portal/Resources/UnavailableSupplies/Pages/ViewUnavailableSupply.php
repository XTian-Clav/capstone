<?php

namespace App\Filament\Portal\Resources\UnavailableSupplies\Pages;

use App\Filament\Portal\Resources\UnavailableSupplies\UnavailableSupplyResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewUnavailableSupply extends ViewRecord
{
    protected static string $resource = UnavailableSupplyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
