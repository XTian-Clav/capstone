<?php

namespace App\Filament\Portal\Pages;

use UnitEnum;
use Filament\Pages\Page;
use App\Models\Equipment;
use Filament\Actions\Action;

class EquipmentReport extends Page
{
    protected static ?int $navigationSort = 2;
    protected static string|UnitEnum|null $navigationGroup = 'Generate Reports';

    protected string $view = 'filament.portal.pages.equipment-report';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('print_report')
                ->label('Print')
                ->icon('heroicon-s-printer')
                ->color('info'),
        ];
    }

    public $equipments;
    public $totalEquipment = 0;
    public $totalAvailable = 0;
    public $totalReserved = 0;
    public $totalUnavailable = 0;
    public $mostBorrowed;
    public $criticalStock;
    public $lowStock;
    public $outOfStock;

    public function mount(): void
    {
        $this->equipments = Equipment::with(['reservations', 'unavailable'])->orderBy('equipment_name', 'asc')->get();

        $this->equipments->transform(function ($equipment) {
            $approvedReservations = $equipment->reservations->where('status', 'Approved');
            $reserved = $equipment->reservations->where('status', 'Approved')->sum('quantity');
            $unavailable = $equipment->unavailable->sum('unavailable_quantity');
            $available = $equipment->quantity - $reserved - $unavailable;

            $equipment->reserved = $reserved;
            $equipment->unavailable_qty = $unavailable;
            $equipment->available = $available;
            $equipment->availability_percentage = $equipment->quantity > 0
                ? ($available / $equipment->quantity) * 100
                : 0;

            $this->totalEquipment += $equipment->quantity;
            $this->totalReserved += $reserved;
            $this->totalUnavailable += $unavailable;

            $equipment->borrow_count = $approvedReservations->count();

            return $equipment;
        });

        $this->totalAvailable = $this->totalEquipment - $this->totalReserved - $this->totalUnavailable;

        $this->mostBorrowed = $this->equipments->sortByDesc('borrow_count')->first();

        $this->lowStock = $this->equipments->filter(fn ($equipment) => 
            $equipment->availability_percentage > 26 && $equipment->availability_percentage <= 50
        );

        $this->criticalStock = $this->equipments->filter(fn ($equipment) => 
            $equipment->available > 0 && $equipment->availability_percentage <= 25
        );

        $this->outOfStock = $this->equipments->filter(fn ($equipment) => 
            $equipment->available <= 0
        );
    }
}