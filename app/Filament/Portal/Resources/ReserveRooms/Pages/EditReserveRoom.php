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
use App\Filament\Portal\Resources\ReserveRooms\Pages\EditReserveRoom;
use App\Filament\Portal\Resources\ReserveRooms\Pages\ViewReserveRoom;

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
        $roomType = $room->room_type ?? ($data['room_type'] ?? 'a room');

        $status = strtolower($data['status'] ?? 'updated');

        Notification::make()
            ->title('Reservation Update')
            ->body('Your reservation for ' . $roomType . ' has been ' . $status . '.')
            ->actions([
                Action::make('view')
                    ->button()
                    ->color('secondary')
                    ->url(ViewReserveRoom::getUrl([
                        'record' => $record->getRouteKey(),
                    ]), shouldOpenInNewTab: true),
            ])
            ->sendToDatabase($owner);

        Notification::make()
            ->title('Reservation Update Sent')
            ->body('You have ' . $status . ' the reservation for ' . $roomType . ' for ' . $owner->name . '.')
            ->actions([
                Action::make('view')
                    ->button()
                    ->color('gray')
                    ->url(ViewReserveRoom::getUrl([
                        'record' => $record->getRouteKey(),
                    ]), shouldOpenInNewTab: true),
                Action::make('edit')
                    ->button()
                    ->color('secondary')
                    ->url(EditReserveRoom::getUrl([
                        'record' => $record->getRouteKey(),
                    ]), shouldOpenInNewTab: true),
            ])
            ->sendToDatabase($admin);

        return parent::mutateFormDataBeforeSave($data);
    }
}
