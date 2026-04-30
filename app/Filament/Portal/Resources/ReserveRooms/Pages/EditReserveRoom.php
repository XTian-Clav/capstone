<?php

namespace App\Filament\Portal\Resources\ReserveRooms\Pages;

use App\Models\Room;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\Size;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Actions\Room\RoomSchedule;
use App\Filament\Portal\Resources\ReserveRooms\ReserveRoomResource;
use App\Filament\Portal\Resources\ReserveRooms\Pages\EditReserveRoom;
use App\Filament\Portal\Resources\ReserveRooms\Pages\ViewReserveRoom;

class EditReserveRoom extends EditRecord
{
    protected static string $resource = ReserveRoomResource::class;

    protected function getHeaderActions(): array
    {
        return [
            RoomSchedule::make()->size(Size::Medium)->label("Room Schedules"),
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
