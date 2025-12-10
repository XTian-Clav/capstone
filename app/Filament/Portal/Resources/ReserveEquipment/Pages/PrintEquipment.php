<?php

namespace App\Filament\Portal\Resources\ReserveEquipment\Pages;

use App\Models\ReserveEquipment;
use Filament\Resources\Pages\Page;
use App\Filament\Actions\BackButton;
use App\Filament\Actions\Print\PrintEquipmentAction;
use App\Filament\Portal\Resources\ReserveEquipment\ReserveEquipmentResource;

class PrintEquipment extends Page
{
    protected static string $resource = ReserveEquipmentResource::class;

    protected string $view = 'filament.portal.resources.reserve-equipment.pages.print-equipment';

    public $record;
    public $reserveEquipment;

    public function mount($record)
    {
        $this->record = $record;
        $this->reserveEquipment = ReserveEquipment::with('equipment')->find($record);
    }

    protected function getHeaderActions(): array
    {
        return [
            BackButton::make(),
            PrintEquipmentAction::make()->url(route('PrintEquipment', ['id' => $this->record])),
        ];
    }
}