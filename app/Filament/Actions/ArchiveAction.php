<?php

namespace App\Filament\Actions;

use Filament\Actions\Action;

class ArchiveAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'archive')
            ->hiddenLabel()
            ->icon('heroicon-s-archive-box-arrow-down')
            ->color('primary')
            ->requiresConfirmation()
            ->modalHeading(fn ($action) => 'Archive ' . $action->getRecordTitle())
            ->modalDescription('Once archived, only Superadmin can restore it.')
            ->modalSubmitActionLabel('Archive')
            ->successNotificationTitle('Archived successfully!')
            ->action(fn ($record) => $record->delete())
            ->visible(fn ($record) => ! ($record?->trashed() ?? false));
    }
}
