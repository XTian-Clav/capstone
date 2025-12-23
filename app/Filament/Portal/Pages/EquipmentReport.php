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

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasAnyRole(['admin', 'super_admin']);
    }

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

    public $totalPending = 0;
    public $totalApproved = 0;
    public $totalRejected = 0;
    public $totalCompleted = 0;

    public $mostBorrowed;
    public $criticalStock;
    public $lowStock;
    public $outOfStock;

    public function mount(): void
    {
        $this->equipments = Equipment::with(['reservations', 'unavailable'])->orderBy('equipment_name', 'asc')->get();

        $this->equipments->transform(function ($equipment) {
            $pending = $equipment->reservations->where('status', 'Pending');
            $approved = $equipment->reservations->where('status', 'Approved');
            $rejected = $equipment->reservations->where('status', 'Rejected');
            $completed = $equipment->reservations->where('status', 'Completed');

            $equipment->pending_count = $pending->count();
            $equipment->approved_count = $approved->count();
            $equipment->rejected_count = $rejected->count();
            $equipment->completed_count = $completed->count();

            $reservedQty = $approved->sum('quantity');
            $unavailableQty = $equipment->unavailable->sum('unavailable_quantity');
            $available = $equipment->quantity - $reservedQty - $unavailableQty;

            $equipment->available = $available;
            $equipment->availability_percentage = $equipment->quantity > 0
                ? ($available / $equipment->quantity) * 100
                : 0;

            $this->totalEquipment += $equipment->quantity;
            $this->totalReserved += $reservedQty;
            $this->totalUnavailable += $unavailableQty;

            $this->totalPending += $equipment->pending_count;
            $this->totalApproved += $equipment->approved_count;
            $this->totalRejected += $equipment->rejected_count;
            $this->totalCompleted += $equipment->completed_count;

            $equipment->borrow_count = $equipment->approved_count + $equipment->completed_count;

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