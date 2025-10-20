<?php

namespace App\Filament\Actions;

use Filament\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection;

class ArchiveBulkAction extends BulkAction
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'archive')
            ->label('Archive Selected')
            ->icon('heroicon-s-archive-box-arrow-down')
            ->color('secondary')
            ->requiresConfirmation()
            ->modalHeading(fn ($action) => 'Archive ' . $action->getTitleCasePluralModelLabel())
            ->modalDescription('Once archived, only Superadmin can restore it.')
            ->modalSubmitActionLabel('Archive')
            ->successNotificationTitle('Archived successfully!')
            ->action(fn ($records) => $records->each->delete())
            ->hidden(fn ($records) => $records->every(fn ($record) => $record->trashed()));
    }
}
