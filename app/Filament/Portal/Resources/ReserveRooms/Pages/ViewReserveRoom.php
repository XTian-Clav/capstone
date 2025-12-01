<?php

namespace App\Filament\Portal\Resources\ReserveRooms\Pages;

use Filament\Actions\EditAction;
use App\Filament\Actions\BackButton;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Actions\Room\RejectRoomAction;
use App\Filament\Actions\Room\ApproveRoomAction;
use App\Filament\Actions\Room\CompleteRoomAction;
use App\Filament\Portal\Resources\ReserveRooms\ReserveRoomResource;

class ViewReserveRoom extends ViewRecord
{
    protected static string $resource = ReserveRoomResource::class;

    protected function getHeaderActions(): array
    {
        return [
            BackButton::make(),
            ApproveRoomAction::make(),
            RejectRoomAction::make(),
            CompleteRoomAction::make(),
        ];
    }
}
