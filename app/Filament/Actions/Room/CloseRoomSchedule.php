<?php

namespace App\Filament\Actions\Room;

use Filament\Actions\Action;
use App\Filament\Portal\Resources\ReserveRooms\ReserveRoomResource;

class CloseRoomSchedule
{
    public static function make(): Action
    {
        return Action::make('view_approved_schedule')
            ->button()
            ->color('zinc')
            ->label('Close')
            ->icon('heroicon-o-x-mark')
            ->url(fn (): string => ReserveRoomResource::getUrl('index'));
    }
}