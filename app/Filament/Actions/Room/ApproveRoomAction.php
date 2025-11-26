<?php

namespace App\Filament\Actions\Room;

use Filament\Actions\Action;
use App\Models\ReserveRoom;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use App\Filament\Portal\Resources\ReserveRooms\Pages\ViewReserveRoom;

class ApproveRoomAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'approve')
            ->label('Approve')
            ->color('success')
            ->icon('heroicon-o-check')
            ->requiresConfirmation()
            ->modalHeading(fn ($action) => 'Approve ' . ($action->getRecord()?->room?->room_type ?? 'Reservation'))
            ->modalDescription('Are you sure you want to approve this reservation?')
            ->modalSubmitActionLabel('Approve')
            ->action(function (ReserveRoom $record) {
                $room = $record->room;
                if (! $room) {
                    Notification::make()
                        ->title('Cannot Approve')
                        ->body('This reservation has no associated room.')
                        ->danger()
                        ->send();
                    return;
                }

                $overlap = ReserveRoom::query()
                    ->where('status', 'Approved')
                    ->where('room_id', $room->id)
                    ->where('id', '!=', $record->id)
                    ->where(function ($q) use ($record) {
                        $q->where('start_date', '<', $record->end_date)
                          ->where('end_date', '>', $record->start_date);
                    })
                    ->exists();

                if ($overlap) {
                    Notification::make()
                        ->title('Cannot Approve')
                        ->body("The room {$room->room_type} is already reserved during this time.")
                        ->danger()
                        ->send();
                    return;
                }

                $record->status = 'Approved';
                $record->save();

                $owner = $record->user;
                $admin = auth()->user();
                $roomType = $room->room_type;

                if ($owner) {
                    Notification::make()
                        ->title('Reservation Approved')
                        ->body("Your reservation for {$roomType} has been approved.")
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
                    ->title('Reservation Approved')
                    ->body("You approved the reservation for {$roomType} for " . ($owner?->name ?? 'Unknown user') . ".")
                    ->sendToDatabase($admin);
            })
            ->visible(fn ($record) =>
                $record?->status === 'Pending' &&
                auth()->user()->hasAnyRole(['super_admin', 'admin'])
            );
    }
}