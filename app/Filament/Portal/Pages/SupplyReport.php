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

    protected function getHeaderActions(): array
    {
        return [
            Action::make('print_report')
                ->label('Print')
                ->icon('heroicon-s-printer')
                ->color('info'),
        ];
    }

    public $supplies;
    public $totalSupply = 0;
    public $totalAvailable = 0;
    public $totalReserved = 0;
    public $totalUnavailable = 0;
    public $mostBorrowed;
    public $criticalStock;
    public $lowStock;
    public $outOfStock;

    public function mount(): void
    {
        $this->supplies = Supply::with(['reservations', 'unavailable'])->get();

        $this->supplies->transform(function ($supply) {
            $approvedReservations = $supply->reservations->where('status', 'Approved');
            $reserved = $supply->reservations->where('status', 'Approved')->sum('quantity');
            $unavailable = $supply->unavailable->sum('unavailable_quantity');
            $available = $supply->quantity - $reserved - $unavailable;

            $supply->reserved = $reserved;
            $supply->unavailable_qty = $unavailable;
            $supply->available = $available;
            $supply->availability_percentage = $supply->quantity > 0
                ? ($available / $supply->quantity) * 100
                : 0;

            $this->totalSupply += $supply->quantity;
            $this->totalReserved += $reserved;
            $this->totalUnavailable += $unavailable;

            $supply->borrow_count = $approvedReservations->count();

            return $supply;
        });

        $this->totalAvailable = $this->totalSupply - $this->totalReserved - $this->totalUnavailable;

        $this->mostBorrowed = $this->supplies->sortByDesc('borrow_count')->first();

        $this->lowStock = $this->supplies->filter(fn ($supply) => 
            $supply->availability_percentage > 20 && $supply->availability_percentage <= 50
        );

        $this->criticalStock = $this->supplies->filter(fn ($supply) => 
            $supply->available > 0 && $supply->availability_percentage <= 20
        );

        $this->outOfStock = $this->supplies->filter(fn ($supply) => 
            $supply->available <= 0
        );
    }
}