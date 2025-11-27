<?php

namespace App\Filament\Portal\Resources\ReserveSupplies\Pages;

use App\Models\User;
use App\Models\Supply;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Portal\Resources\ReserveSupplies\ReserveSupplyResource;
use App\Filament\Portal\Resources\ReserveSupplies\Pages\EditReserveSupply;
use App\Filament\Portal\Resources\ReserveSupplies\Pages\ViewReserveSupply;

class EditReserveSupply extends EditRecord
{
    protected static string $resource = ReserveSupplyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
