<?php

namespace App\Filament\Portal\Resources\Supplies\Pages;

use Filament\Actions\Action;
use Filament\Actions\EditAction;
use App\Filament\Actions\BackButton;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Portal\Resources\Supplies\SupplyResource;

class ViewSupply extends ViewRecord
{
    protected static string $resource = SupplyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            BackButton::make(),
            EditAction::make(),
        ];
    }
}
