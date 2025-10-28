<?php

namespace App\Filament\Portal\Resources\ReserveRooms\Pages;

use App\Models\Room;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Portal\Resources\ReserveRooms\ReserveRoomResource;

class EditReserveRoom extends EditRecord
{
    protected static string $resource = ReserveRoomResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $record = $this->record;

        $admin = auth()->user();
        $owner = User::find($record->user_id ?? $data['user_id'] ?? null);

        if (! $owner) {
            return parent::mutateFormDataBeforeSave($data);
        }

        $room = $record->room ?? Room::find($data['room_id'] ?? null);
        $roomName = $room->room_name ?? ($data['room_name'] ?? 'a room');

        $status = strtolower($data['status'] ?? 'updated');

        Notification::make()
            ->title('Reservation Update')
            ->body('The reservation for ' . $roomName . ' has been ' . $status . '.')
            ->sendToDatabase($owner);

        Notification::make()
            ->title('Reservation Update Sent')
            ->body('You have ' . $status . ' the reservation for ' . $roomName . '.')
            ->sendToDatabase($admin);

        return parent::mutateFormDataBeforeSave($data);
    }
}
