<?php

namespace App\Filament\Portal\Resources\Guides\Pages;

use App\Filament\Portal\Resources\Guides\GuideResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewGuide extends ViewRecord
{
    protected static string $resource = GuideResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
