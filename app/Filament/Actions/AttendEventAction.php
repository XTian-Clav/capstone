<?php

namespace App\Filament\Actions;

use App\Models\User;
use App\Models\Event;
use Filament\Actions\Action;
use Filament\Forms\Components\Radio;
use App\Notifications\EventAttendance;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;

class AttendEventAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'attend')
            ->button()
            ->label('Register Attendance')
            ->color('warning')
            ->icon('heroicon-o-clipboard-document-check')
            ->requiresConfirmation() 
            ->modalHeading(fn (Event $record) => 'Confirm Attendance for ' . ($record->event ?? 'the Event'))
            ->modalDescription('Please confirm your intended attendance status for this event.')
            ->modalSubmitActionLabel('Confirm')
            ->schema([
                Section::make()
                ->schema([
                    Radio::make('is_attending')
                    ->label('Are you attending this event?')
                    ->options([
                        true => 'Yes', 
                        false => 'No',
                    ])
                    ->descriptions([
                        true => 'I will go to the event', 
                        false => 'I will not go to the event',
                    ])
                    ->default(true)
                    ->inline()
                    ->required(),
                ])
            ])
            
            ->action(function (Event $record, array $data) {
                /** @var User $user */
                $user = auth()->user();
                $isAttending = (bool) $data['is_attending'];

                $record->attendees()->syncWithoutDetaching([
                    $user->id => ['is_attending' => $isAttending]
                ]);

                $user->notify(new EventAttendance($record, $isAttending));
            })
            ->hidden(fn (Event $record): bool =>
                $record->attendees()->where('user_id', auth()->id())->exists()
            )
            ->visible(fn (Event $record): bool =>
                in_array($record?->status, ['Upcoming', 'Ongoing']) &&
                auth()->user()->hasAnyRole(['incubatee', 'investor'])
            );
    }
}