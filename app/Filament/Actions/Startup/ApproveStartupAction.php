<?php

namespace App\Filament\Actions\Startup;

use App\Models\Startup;
use Filament\Actions\Action;
use Filament\Support\Enums\Size;
use App\Notifications\StartupApproved;
use Filament\Notifications\Notification;
use App\Filament\Portal\Resources\Startups\Pages\ViewStartup;

class ApproveStartupAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'approve')
            ->button()
            ->label('Approve')
            ->color('success')
            ->icon('heroicon-m-check-circle')
            ->requiresConfirmation()
            ->modalHeading(fn ($action) => 'Approve ' . ($action->getRecord()?->startup_name ?? 'Startup'))
            ->modalDescription('Are you sure you want to approve this startup proposal?')
            ->modalSubmitActionLabel('Approve')
            ->action(function (Startup $record) {
                $startupName = $record->startup_name ?? 'a startup';

                $record->status = 'Approved';
                $record->save();

                $owner = $record->user;
                $admin = auth()->user();

                if ($owner) {
                    $owner->notify(new StartupApproved($record));
                }

                Notification::make()
                    ->color('success')
                    ->iconColor('sucess')
                    ->icon('heroicon-m-check-circle')
                    ->title('Startup Proposal Approved')
                    ->body("You have approved the startup proposal <strong>{$startupName}</strong> for " . ($owner?->name ?? 'Unknown user') . ".")
                    ->sendToDatabase($admin);
            })
            ->visible(fn ($record) =>
                $record?->status === 'Pending' &&
                auth()->user()->hasAnyRole(['super_admin', 'admin'])
            );
    }
}
