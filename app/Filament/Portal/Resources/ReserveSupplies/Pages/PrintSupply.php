<?php

namespace App\Filament\Portal\Resources\ReserveSupplies\Pages;

use App\Models\ReserveSupply;
use Filament\Resources\Pages\Page;
use App\Filament\Actions\BackButton;
use App\Filament\Actions\Print\PrintSupplyAction;
use App\Filament\Portal\Resources\ReserveSupplies\ReserveSupplyResource;

class PrintSupply extends Page
{
    protected static string $resource = ReserveSupplyResource::class;

    protected string $view = 'filament.portal.resources.reserve-supplies.pages.print-supply';

    public $record;
    public $reserveSupply;

    public function mount($record)
    {
        $this->record = $record;
        $this->reserveSupply = ReserveSupply::with('supply')->find($record);
    }

    protected function getHeaderActions(): array
    {
        return [
            BackButton::make(),
            PrintSupplyAction::make()->url(route('PrintSupply', ['id' => $this->record])),
        ];
    }
}
