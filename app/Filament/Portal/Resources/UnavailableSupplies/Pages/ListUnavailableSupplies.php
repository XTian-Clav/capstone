<?php

namespace App\Filament\Portal\Resources\UnavailableSupplies\Pages;

use App\Filament\Portal\Resources\UnavailableSupplies\UnavailableSupplyResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListUnavailableSupplies extends ListRecords
{
    protected static string $resource = UnavailableSupplyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
