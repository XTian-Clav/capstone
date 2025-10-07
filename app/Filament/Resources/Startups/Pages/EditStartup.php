<?php

namespace App\Filament\Resources\Startups\Pages;

use App\Filament\Resources\Startups\StartupResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions;

class EditStartup extends EditRecord
{
    protected static string $resource = StartupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            Actions\RestoreAction::make()->color('success'),
            Actions\ForceDeleteAction::make(),
        ];
    }
}
