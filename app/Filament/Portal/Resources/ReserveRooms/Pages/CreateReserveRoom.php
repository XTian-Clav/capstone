<?php

namespace App\Filament\Portal\Resources\ReserveRooms\Pages;

use App\Filament\Portal\Resources\ReserveRooms\ReserveRoomResource;
use Filament\Resources\Pages\CreateRecord;

class CreateReserveRoom extends CreateRecord
{
    protected static string $resource = ReserveRoomResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = auth()->user();

        if (! $user->hasAnyRole(['admin', 'super_admin'])) {
            $data['status'] = 'Pending';
        }

        return $data;
    }
}
