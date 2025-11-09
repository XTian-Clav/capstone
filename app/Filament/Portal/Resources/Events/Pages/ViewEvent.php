<?php

namespace App\Filament\Portal\Resources\Events\Pages;

use App\Filament\Portal\Resources\Events\EventResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewEvent extends ViewRecord
{
    protected static string $resource = EventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url(static::getResource()::getUrl('index')),
            
            EditAction::make()
                ->color('secondary')
                ->label('Submit Attendance')
                ->icon('heroicon-s-clipboard-document-check')
                ->visible(fn () => auth()->user()?->hasAnyRole(['incubatee', 'investor'])),
        ];
    }
}
