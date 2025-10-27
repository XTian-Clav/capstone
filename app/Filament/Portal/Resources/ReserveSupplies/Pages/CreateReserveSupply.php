<?php

namespace App\Filament\Portal\Resources\ReserveSupplies\Pages;

use App\Filament\Portal\Resources\ReserveSupplies\ReserveSupplyResource;
use Filament\Resources\Pages\CreateRecord;

class CreateReserveSupply extends CreateRecord
{
    protected static string $resource = ReserveSupplyResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        return $data;
    }
}
