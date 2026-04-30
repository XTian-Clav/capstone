<?php

namespace App\Filament\Portal\Resources\ReserveRooms\Pages;

use App\Models\Room;
use Filament\Support\Enums\Size;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Actions\Room\RoomSchedule;
use App\Filament\Portal\Resources\ReserveRooms\ReserveRoomResource;

class CreateReserveRoom extends CreateRecord
{
    protected static string $resource = ReserveRoomResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        $user = auth()->user();

        $name = Room::find($data['room_id'] ?? null)?->room_type ?? 'a room';
        $roomName = "<strong>{$name}</strong>";

        if (! $user->hasAnyRole(['admin', 'super_admin'])) {
            Notification::make()
                ->color('warning')
                ->iconColor('warning')
                ->icon('heroicon-m-clock')
                ->title('Room Reservation Submitted')
                ->body('Your reservation for ' . $roomName . ' has been submitted successfully.')
                ->sendToDatabase($user);
        }

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            RoomSchedule::make()->size(Size::Medium)->label("Room Schedules"),
        ];
    }
}
