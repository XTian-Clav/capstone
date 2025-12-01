<?php

namespace App\Filament\Actions\Startup;

use App\Models\Startup;
use Filament\Actions\Action;
use Filament\Support\Enums\Size;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use App\Filament\Portal\Resources\Startups\Pages\ViewStartup;

class RejectstartupAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'reject')
            ->button()
            ->label('Reject')
            ->color('danger')
            ->icon('heroicon-o-x-mark')
            ->requiresConfirmation()
            ->modalHeading(fn ($action) => 'Reject ' . ($action->getRecord()?->startup_name ?? 'Startup'))
            ->modalDescription('Are you sure you want to reject this startup proposal?')
            ->modalSubmitActionLabel('Reject')
            ->schema([
                Textarea::make('admin_comment')
                    ->label('Reason for rejection')
                    ->required()
                    ->rows(6)
                    ->placeholder('Enter reason for rejection...'),
            ])
            ->action(function (Startup $record, array $data) {

                $startupName = $record->startup_name ?? 'a startup';
                $owner = $record->user;
                $admin = auth()->user();

                $record->status = 'Rejected';
                $record->admin_comment = $data['admin_comment'] ?? null;
                $record->save();

                if ($owner) {
                    Notification::make()
                        ->danger()
                        ->color('danger')
                        ->title('Startup Proposal Rejected')
                        ->body("Your startup proposal titled {$startupName} has been rejected.")
                        ->actions([
                            Action::make('view')
                                ->button()
                                ->outlined()
                                ->color('secondary')
                                ->url(ViewStartup::getUrl([
                                    'record' => $record->getRouteKey(),
                                ]), shouldOpenInNewTab: true),
                        ])
                        ->sendToDatabase($owner);
                }

                Notification::make()
                    ->title('Startup Proposal Rejected')
                    ->body("You have rejected the startup proposal {$startupName} for " . ($owner?->name ?? 'Unknown user') . ".")
                    ->sendToDatabase($admin);
            })
            ->visible(fn ($record) =>
                in_array($record?->status, ['Pending', 'Approved']) &&
                auth()->user()->hasAnyRole(['super_admin', 'admin'])
            );
    }
}
