<?php

namespace App\Filament\Portal\Resources\ReserveSupplies\Pages;

use App\Filament\Portal\Resources\ReserveSupplies\ReserveSupplyResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListReserveSupplies extends ListRecords
{
    protected static string $resource = ReserveSupplyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
