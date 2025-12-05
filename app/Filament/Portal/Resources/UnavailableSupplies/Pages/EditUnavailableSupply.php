<?php

namespace App\Filament\Portal\Resources\UnavailableSupplies\Pages;

use App\Filament\Portal\Resources\UnavailableSupplies\UnavailableSupplyResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditUnavailableSupply extends EditRecord
{
    protected static string $resource = UnavailableSupplyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
