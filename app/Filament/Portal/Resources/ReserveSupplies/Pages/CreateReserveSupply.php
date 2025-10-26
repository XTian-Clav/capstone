<?php

namespace App\Filament\Portal\Resources\ReserveSupplies\Pages;

use App\Filament\Portal\Resources\ReserveSupplies\ReserveSupplyResource;
use Filament\Resources\Pages\CreateRecord;

class CreateReserveSupply extends CreateRecord
{
    protected static string $resource = ReserveSupplyResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = auth()->user();

        if (! $user->hasAnyRole(['admin', 'super_admin'])) {
            $data['status'] = 'Pending';
        }

        return $data;
    }
}
