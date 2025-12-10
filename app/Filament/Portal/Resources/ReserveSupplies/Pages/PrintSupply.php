<?php

namespace App\Filament\Portal\Resources\ReserveSupplies\Pages;

use Filament\Resources\Pages\Page;
use App\Filament\Actions\BackButton;
use App\Filament\Portal\Resources\ReserveSupplies\ReserveSupplyResource;

class PrintSupply extends Page
{
    protected static string $resource = ReserveSupplyResource::class;

    protected string $view = 'filament.portal.resources.reserve-supplies.pages.print-supply';

    protected function getHeaderActions(): array
    {
        return [
            BackButton::make(),
        ];
    }
}
