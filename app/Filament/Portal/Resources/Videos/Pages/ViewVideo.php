<?php

namespace App\Filament\Portal\Resources\Videos\Pages;

use App\Filament\Portal\Resources\Videos\VideoResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewVideo extends ViewRecord
{
    protected static string $resource = VideoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
