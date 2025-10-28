<?php

namespace App\Filament\Portal\Resources\ReserveRooms\Pages;

use App\Models\Room;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Portal\Resources\ReserveRooms\ReserveRoomResource;

class CreateReserveRoom extends CreateRecord
{
    protected static string $resource = ReserveRoomResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        $user = auth()->user();
        $roomName = Room::find($data['room_id'] ?? null)?->room_name ?? 'a room';

        if (! $user->hasAnyRole(['admin', 'super_admin'])) {
            Notification::make()
                ->title('Reservation Submitted')
                ->body('Your reservation for ' . $roomName . ' has been submitted successfully.')
                ->sendToDatabase($user);
        }

        return $data;
    }
}
