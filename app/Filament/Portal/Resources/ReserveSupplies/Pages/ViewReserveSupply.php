<?php

namespace App\Filament\Portal\Resources\ReserveSupplies\Pages;

use Filament\Actions\EditAction;
use App\Filament\Actions\BackButton;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Actions\Supply\RejectSupplyAction;
use App\Filament\Actions\Supply\ApproveSupplyAction;
use App\Filament\Actions\Supply\CompleteSupplyAction;
use App\Filament\Portal\Resources\ReserveSupplies\ReserveSupplyResource;

class ViewReserveSupply extends ViewRecord
{
    protected static string $resource = ReserveSupplyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            BackButton::make(),
            ApproveSupplyAction::make(),
            RejectSupplyAction::make(),
            CompleteSupplyAction::make(),
        ];
    }
}
