<?php

namespace App\Filament\Portal\Resources\Startups\Pages;

use Filament\Actions\Action;
use Filament\Actions\EditAction;
use App\Filament\Actions\BackButton;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Actions\Startup\RejectStartupAction;
use App\Filament\Actions\Startup\ApproveStartupAction;
use App\Filament\Portal\Resources\Startups\StartupResource;

class ViewStartup extends ViewRecord
{
    protected static string $resource = StartupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            BackButton::make(),
            ApproveStartupAction::make(),
            RejectStartupAction::make(),
        ];
    }
}
