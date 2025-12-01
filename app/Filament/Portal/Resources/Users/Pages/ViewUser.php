<?php

namespace App\Filament\Portal\Resources\Users\Pages;

use Filament\Actions\EditAction;
use App\Filament\Actions\BackButton;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Portal\Resources\Users\UserResource;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            BackButton::make(),
            EditAction::make(),
        ];
    }
}
