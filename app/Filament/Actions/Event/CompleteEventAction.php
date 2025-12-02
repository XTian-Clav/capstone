<?php

namespace App\Filament\Actions\Event;

use App\Models\Event;
use Filament\Actions\Action;
use Filament\Support\Enums\Size;
use Filament\Notifications\Notification;
use App\Filament\Portal\Resources\Events\Pages\ViewEvent;

class CompleteEventAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'complete')
            ->button()
            ->label('Mark as Completed')
            ->color('cyan')
            ->icon('heroicon-m-check-badge')
            ->requiresConfirmation()
            ->modalHeading(fn ($action) => 'Complete ' . ($action->getRecord()?->event ?? 'event'))
            ->modalDescription('Are you sure you want to mark this event as completed?')
            ->modalSubmitActionLabel('Complete')
            ->action(function (Event $record) {
                $eventName = $record->event ?? 'an event';
                $record->status = 'Completed';
                $record->save();

                Notification::make()
                    ->title('Event Completed')
                    ->success()
                    ->send();
            })
            ->visible(fn ($record) =>
                $record?->status === 'Ongoing' &&
                auth()->user()->hasAnyRole(['super_admin', 'admin'])
            );
    }
}