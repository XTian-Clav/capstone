<?php

namespace App\Filament\Actions\Startup;

use App\Models\Startup;
use Filament\Actions\Action;
use Filament\Support\Enums\Size;
use App\Notifications\StartupRejected;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use App\Filament\Portal\Resources\Startups\Pages\ViewStartup;

class RejectStartupAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'reject')
            ->button()
            ->label('Reject')
            ->color('danger')
            ->icon('heroicon-m-x-circle')
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
                    $owner->notify(new StartupRejected($record));
                }

                Notification::make()
                    ->color('danger')
                    ->iconColor('danger')
                    ->icon('heroicon-m-x-circle')
                    ->title('Startup Proposal Rejected')
                    ->body("You have rejected the startup proposal <strong>{$startupName}</strong> for " . ($owner?->name ?? 'Unknown user') . ".")
                    ->sendToDatabase($admin);
            })
            ->visible(fn ($record) =>
                in_array($record?->status, ['Pending', 'Approved']) &&
                auth()->user()->hasAnyRole(['super_admin', 'admin'])
            );
    }
}
