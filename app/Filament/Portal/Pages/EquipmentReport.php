<?php

namespace App\Filament\Portal\Pages;

use UnitEnum;
use Filament\Pages\Page;
use App\Models\Equipment;
use Filament\Actions\Action;
use App\Models\ReserveEquipment;

class EquipmentReport extends Page
{
    protected static ?int $navigationSort = 2;
    protected static string|UnitEnum|null $navigationGroup = 'Generate Reports';

    protected string $view = 'filament.portal.pages.equipment-report';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasRole('super_admin');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('print_report')
                ->label('Print')
                ->icon('heroicon-s-printer')
                ->color('info')
                ->url(route('EquipmentReport', request()->only(['month', 'year'])))
                ->openUrlInNewTab(),
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

    public $availableYears = [];

    public function mount(): void
    {
        if (!request()->query('month') && !request()->query('year')) {
            $this->redirect(request()->fullUrlWithQuery([
                'month' => now()->month,
                'year' => now()->year,
            ]));
            return;
        }

        $month = request('month');
        $year = request('year', now()->year);

        $this->availableYears = ReserveEquipment::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year')
            ->toArray() ?: [now()->year];

        $this->equipments = Equipment::withSum('unavailable as total_unavailable_qty', 'unavailable_quantity')
            ->withSum(['reservations as current_reserved_qty' => fn ($q) => $q->where('status', 'Approved')], 'quantity')
            ->withCount([
                'reservations as pending_count' => fn ($q) => $q->where('status', 'Pending')
                    ->whereYear('created_at', $year)
                    ->when($month, fn($query) => $query->whereMonth('created_at', $month)),
                
                'reservations as approved_count' => fn ($q) => $q->where('status', 'Approved')
                    ->whereYear('created_at', $year)
                    ->when($month, fn($query) => $query->whereMonth('created_at', $month)),

                'reservations as rejected_count' => fn ($q) => $q->where('status', 'Rejected')
                    ->whereYear('created_at', $year)
                    ->when($month, fn($query) => $query->whereMonth('created_at', $month)),

                'reservations as completed_count' => fn ($q) => $q->where('status', 'Completed')
                    ->whereYear('created_at', $year)
                    ->when($month, fn($query) => $query->whereMonth('created_at', $month)),
            ])
            ->orderBy('equipment_name', 'asc')
            ->get();

        foreach ($this->equipments as $equipment) {
            $equipment->borrow_count = $equipment->approved_count + $equipment->completed_count;
            
            $equipment->available = $equipment->quantity 
                - ($equipment->current_reserved_qty ?? 0) 
                - ($equipment->total_unavailable_qty ?? 0);
            
            $equipment->availability_percentage = $equipment->quantity > 0 
                ? ($equipment->available / $equipment->quantity) * 100 
                : 0;

            $this->totalPending += $equipment->pending_count;
            $this->totalApproved += $equipment->approved_count;
            $this->totalRejected += $equipment->rejected_count;
            $this->totalCompleted += $equipment->completed_count;
            
            $this->totalEquipment += $equipment->quantity;
            $this->totalReserved += ($equipment->current_reserved_qty ?? 0);
            $this->totalUnavailable += ($equipment->total_unavailable_qty ?? 0);
        }

        $this->totalAvailable = $this->totalEquipment - $this->totalReserved - $this->totalUnavailable;
        
        $this->mostBorrowed = $this->equipments->sortByDesc('borrow_count')->first();
        $this->lowStock = $this->equipments->filter(fn ($e) => $e->availability_percentage > 26 && $e->availability_percentage <= 50);
        $this->criticalStock = $this->equipments->filter(fn ($e) => $e->available > 0 && $e->availability_percentage <= 25);
        $this->outOfStock = $this->equipments->filter(fn ($e) => $e->available <= 0);
    }
}