<?php

namespace App\Filament\Actions\Room;

use Filament\Actions\Action;
use App\Models\ReserveRoom;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use App\Filament\Portal\Resources\ReserveRooms\Pages\ViewReserveRoom;

class RejectRoomAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'reject')
            ->label('Reject')
            ->color('danger')
            ->icon('heroicon-o-x-mark')
            ->requiresConfirmation()
            ->modalHeading(fn ($action) => 'Reject ' . ($action->getRecord()?->room?->room_type ?? 'Reservation'))
            ->modalDescription('Are you sure you want to reject this reservation?')
            ->modalSubmitActionLabel('Reject')
            ->schema([
                Textarea::make('admin_comment')
                    ->label('Reason for rejection')
                    ->required()
                    ->rows(6)
                    ->placeholder('Enter reason for rejection...'),
            ])
            ->action(function (ReserveRoom $record, array $data) {
                $room = $record->room;
                if (! $room) {
                    Notification::make()
                        ->title('Cannot Reject')
                        ->body('This reservation has no associated room.')
                        ->danger()
                        ->send();
                    return;
                }

                if ($record->status === 'Approved') {
                    $room->is_available = true;
                    $room->save();
                }

                $record->status = 'Rejected';
                $record->admin_comment = $data['admin_comment'] ?? null;
                $record->save();

                $owner = $record->user;
                $admin = auth()->user();
                $roomType = $room->room_type;

                if ($owner) {
                    Notification::make()
                        ->title('Reservation Rejected')
                        ->body("Your reservation for {$roomType} has been rejected.")
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
                    ->title('Reservation Rejected')
                    ->body("You rejected the reservation for {$roomType} for " . ($owner?->name ?? 'Unknown user') . ".")
                    ->sendToDatabase($admin);
            })
            ->visible(fn ($record) =>
                in_array($record?->status, ['Pending', 'Approved']) &&
                auth()->user()->hasAnyRole(['super_admin', 'admin'])
            );
    }
}
