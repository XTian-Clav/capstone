<?php

namespace App\Filament\Actions\Event;

use App\Models\Event;
use Filament\Actions\Action;
use Filament\Support\Enums\Size;
use Filament\Notifications\Notification;
use App\Filament\Portal\Resources\Events\Pages\ViewEvent;

class CancelEventAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'cancel')
            ->button()
            ->label('Cancel Event')
            ->color('danger')
            ->icon('heroicon-o-x-mark')
            ->requiresConfirmation()
            ->modalHeading(fn ($action) => 'Cancel ' . ($action->getRecord()?->event ?? 'event'))
            ->modalDescription('Are you sure you want to cancel this event?')
            ->modalSubmitActionLabel('Cancel')
            ->action(function (Event $record) {
                $eventName = $record->event ?? 'an event';
                $record->status = 'Cancelled';
                $record->save();

                Notification::make()
                    ->title('Event Cancelled')
                    ->danger()
                    ->send();
            })
            ->visible(fn ($record) =>
                $record?->status === 'Upcoming' &&
                auth()->user()->hasAnyRole(['super_admin', 'admin'])
            );
    }
}