<?php

namespace App\Filament\Resources\ReserveRooms\Pages;

use App\Filament\Resources\ReserveRooms\ReserveRoomResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewReserveRoom extends ViewRecord
{
    protected static string $resource = ReserveRoomResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
