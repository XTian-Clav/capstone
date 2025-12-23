<?php

namespace App\Filament\Portal\Pages;

use UnitEnum;
use App\Models\Supply;
use Filament\Pages\Page;
use Filament\Actions\Action;

class SupplyReport extends Page
{
    protected static ?int $navigationSort = 3;
    protected static string|UnitEnum|null $navigationGroup = 'Generate Reports';

    protected string $view = 'filament.portal.pages.supply-report';

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
                ->color('info')
                ->url(route('SupplyReport'))
                ->openUrlInNewTab(),
        ];
    }

    public $supplies;
    public $totalSupply = 0;
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
        $this->supplies = Supply::with(['reservations', 'unavailable'])->orderBy('item_name', 'asc')->get();

        $this->supplies->transform(function ($supply) {
            $pending = $supply->reservations->where('status', 'Pending');
            $approved = $supply->reservations->where('status', 'Approved');
            $rejected = $supply->reservations->where('status', 'Rejected');
            $completed = $supply->reservations->where('status', 'Completed');

            $supply->pending_count = $pending->count();
            $supply->approved_count = $approved->count();
            $supply->rejected_count = $rejected->count();
            $supply->completed_count = $completed->count();

            $reservedQty = $approved->sum('quantity');
            $unavailableQty = $supply->unavailable->sum('unavailable_quantity');
            $available = $supply->quantity - $reservedQty - $unavailableQty;

            $supply->reserved = $reservedQty;
            $supply->unavailable_qty = $unavailableQty;
            $supply->available = $available;
            $supply->availability_percentage = $supply->quantity > 0
                ? ($available / $supply->quantity) * 100
                : 0;

            $this->totalSupply += $supply->quantity;
            $this->totalReserved += $reservedQty;
            $this->totalUnavailable += $unavailableQty;

            $this->totalPending += $supply->pending_count;
            $this->totalApproved += $supply->approved_count;
            $this->totalRejected += $supply->rejected_count;
            $this->totalCompleted += $supply->completed_count;

            $supply->borrow_count = $supply->approved_count + $supply->completed_count;

            return $supply;
        });

        $this->totalAvailable = $this->totalSupply - $this->totalReserved - $this->totalUnavailable;

        $this->mostBorrowed = $this->supplies->sortByDesc('borrow_count')->first();

        $this->lowStock = $this->supplies->filter(fn ($supply) => 
            $supply->availability_percentage > 26 && $supply->availability_percentage <= 50
        );

        $this->criticalStock = $this->supplies->filter(fn ($supply) => 
            $supply->available > 0 && $supply->availability_percentage <= 25
        );

        $this->outOfStock = $this->supplies->filter(fn ($supply) => 
            $supply->available <= 0
        );
    }
}