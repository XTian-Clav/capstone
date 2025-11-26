<?php

namespace App\Filament\Actions\Room;

use Filament\Actions\Action;
use App\Models\ReserveRoom;
use Filament\Notifications\Notification;
use App\Filament\Portal\Resources\ReserveRooms\Pages\ViewReserveRoom;

class CompleteRoomAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'complete')
            ->label('Complete')
            ->color('cyan')
            ->icon('heroicon-m-check-badge')
            ->requiresConfirmation()
            ->modalHeading(fn ($action) => 'Complete ' . ($action->getRecord()?->room?->room_type ?? 'Reservation'))
            ->modalDescription('Mark this reservation as completed? The room will become available again.')
            ->modalSubmitActionLabel('Complete')
            ->action(function (ReserveRoom $record) {
                $room = $record->room;
                if (! $room) {
                    Notification::make()
                        ->title('Cannot Complete')
                        ->body('This reservation has no associated room.')
                        ->danger()
                        ->send();
                    return;
                }

                $record->status = 'Completed';
                $record->save();

                $room->is_available = true;
                $room->save();

                $owner = $record->user;
                $admin = auth()->user();
                $roomType = $room->room_type;

                if ($owner) {
                    Notification::make()
                        ->title('Reservation Completed')
                        ->body("Your reservation for {$roomType} has been completed.")
                        ->actions([
                            Action::make('view')
                                ->button()
                                ->color('secondary')
                                ->url(ViewReserveRoom::getUrl([
                                    'record' => $record->getRouteKey(),
                                ]), shouldOpenInNewTab: true),
                        ])
                        ->sendToDatabase($owner);
                }

                Notification::make()
                    ->title('Reservation Completed')
                    ->body("You completed the reservation for {$roomType} for " . ($owner?->name ?? 'Unknown user') . ".")
                    ->sendToDatabase($admin);
            })
            ->visible(fn ($record) =>
                $record?->status === 'Approved' &&
                auth()->user()->hasAnyRole(['super_admin', 'admin'])
            );
    }
}
