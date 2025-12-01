<?php

namespace App\Filament\Portal\Resources\Events\Pages;

use Filament\Actions\Action;
use Filament\Actions\EditAction;
use App\Filament\Actions\BackButton;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Portal\Resources\Events\EventResource;

class ViewEvent extends ViewRecord
{
    protected static string $resource = EventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            BackButton::make(),
            EditAction::make()
                ->color('secondary')
                ->label('Submit Attendance')
                ->icon('heroicon-s-clipboard-document-check')
                ->visible(fn () => auth()->user()?->hasAnyRole(['incubatee', 'investor'])),
        ];
    }
}
