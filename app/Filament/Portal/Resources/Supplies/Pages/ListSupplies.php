<?php

namespace App\Filament\Portal\Resources\Supplies\Pages;

use App\Filament\Portal\Resources\Supplies\SupplyResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSupplies extends ListRecords
{
    protected static string $resource = SupplyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
