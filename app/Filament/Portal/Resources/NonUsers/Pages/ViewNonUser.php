<?php

namespace App\Filament\Portal\Resources\NonUsers\Pages;

use App\Filament\Portal\Resources\NonUsers\NonUserResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewNonUser extends ViewRecord
{
    protected static string $resource = NonUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
