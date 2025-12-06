<?php

namespace App\Filament\Actions\Room;

use App\Models\ReserveRoom;
use Filament\Actions\Action;
use Filament\Support\Enums\Size;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use App\Filament\Portal\Resources\ReserveRooms\Pages\ViewReserveRoom;

class ApproveRoomAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'approve')
            ->button()
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
                        ->success()
                        ->color('success')
                        ->title('Reservation Approved')
                        ->body("Your reservation for <strong>{$roomType}</strong> has been approved.")
                        ->actions([
                            Action::make('view')
                                ->button()
                                ->outlined()
                                ->color('gray')
                                ->url(ViewReserveRoom::getUrl([
                                    'record' => $record->getRouteKey(),
                                ]), shouldOpenInNewTab: true),
                        ])
                        ->sendToDatabase($owner);
                }

                Notification::make()
                    ->success()
                    ->color('success')
                    ->title('Reservation Approved')
                    ->body("You approved the reservation for <strong>{$roomType}</strong> for " . ($owner?->name ?? 'Unknown user') . ".")
                    ->sendToDatabase($admin);
            })
            ->visible(fn ($record) =>
                $record?->status === 'Pending' &&
                auth()->user()->hasAnyRole(['super_admin', 'admin'])
            );
    }
}