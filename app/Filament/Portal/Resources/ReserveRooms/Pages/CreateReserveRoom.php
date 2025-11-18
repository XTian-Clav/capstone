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
        $roomType = Room::find($data['room_id'] ?? null)?->room_type ?? 'a room';

        if (! $user->hasAnyRole(['admin', 'super_admin'])) {
            Notification::make()
                ->title('Reservation Submitted')
                ->body('Your reservation for ' . $roomType . ' has been submitted successfully.')
                ->sendToDatabase($user);
        }

        return $data;
    }
}
