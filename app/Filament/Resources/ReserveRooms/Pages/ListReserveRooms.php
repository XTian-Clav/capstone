<?php

namespace App\Filament\Resources\ReserveRooms\Pages;

use App\Filament\Resources\ReserveRooms\ReserveRoomResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListReserveRooms extends ListRecords
{
    protected static string $resource = ReserveRoomResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
