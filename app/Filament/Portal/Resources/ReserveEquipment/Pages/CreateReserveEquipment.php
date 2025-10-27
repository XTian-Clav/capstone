<?php

namespace App\Filament\Portal\Resources\ReserveEquipment\Pages;

use App\Filament\Portal\Resources\ReserveEquipment\ReserveEquipmentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateReserveEquipment extends CreateRecord
{
    protected static string $resource = ReserveEquipmentResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        return $data;
    }
}
