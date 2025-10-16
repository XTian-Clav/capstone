<?php

namespace App\Filament\Portal\Resources\Startups\Pages;

use App\Filament\Portal\Resources\Startups\StartupResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewStartup extends ViewRecord
{
    protected static string $resource = StartupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url(static::getResource()::getUrl('index')),
            
            EditAction::make(),
        ];
    }
}
