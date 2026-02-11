<?php

namespace App\Filament\Actions\Event;

use App\Models\Event;
use Filament\Actions\Action;
use Filament\Support\Enums\Size;
use Filament\Notifications\Notification;
use App\Filament\Portal\Resources\Events\Pages\ViewEvent;

class StartEventAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'Start')
            ->button()
            ->label('Start Event')
            ->color('success')
            ->icon('heroicon-o-check')
            ->requiresConfirmation()
            ->modalHeading(fn ($action) => 'Start ' . ($action->getRecord()?->event ?? 'event'))
            ->modalDescription('Are you sure you want to Start this event?')
            ->modalSubmitActionLabel('Start')
            ->action(function (Event $record) {
                $eventName = $record->event ?? 'an event';
                $record->status = 'Ongoing';
                $record->save();

                Notification::make()
                    ->title('Event Started')
                    ->danger()
                    ->send();
            })
            ->visible(fn ($record) =>
                $record?->status === 'Upcoming' &&
                auth()->user()->hasAnyRole(['super_admin', 'admin'])
            );
    }
}